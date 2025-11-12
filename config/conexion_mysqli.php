<?php
/*
 * Conexión MySQL dinámica: intenta leer variables de entorno (útil en Docker)
 * y cae a valores por defecto compatibles con instalaciones locales.
 */

// Hacer que mysqli lance excepciones para capturarlas si hace falta
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$dbHost = getenv('APP_DB_HOST') !== false && getenv('APP_DB_HOST') !== '' ? getenv('APP_DB_HOST') : 'localhost';
$dbUser = getenv('APP_DB_USER') !== false && getenv('APP_DB_USER') !== '' ? getenv('APP_DB_USER') : 'root';
$dbPass = getenv('APP_DB_PASS') !== false && getenv('APP_DB_PASS') !== '' ? getenv('APP_DB_PASS') : '';
// En Docker usamos 'edulinker' como nombre de BD por defecto; en entorno local puede ser distinto
$dbName = getenv('APP_DB_NAME') !== false && getenv('APP_DB_NAME') !== '' ? getenv('APP_DB_NAME') : 'edulinker';

try {
  // Forzar conexión TCP cuando el host es '127.0.0.1' o cualquier nombre resolvible
  $conexion = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
} catch (mysqli_sql_exception $e) {
  // Mostrar mensaje más amigable y terminar (se puede loguear en producción)
  http_response_code(500);
  echo "Fatal error: could not connect to database ({$dbHost}): " . htmlspecialchars($e->getMessage());
  exit;
}

// Verificar la conexión (por si mysqli no lanzó excepción)
if ($conexion->connect_error) {
  http_response_code(500);
  die("Error al intentar conectarse a la base de datos: " . $conexion->connect_error);
}

