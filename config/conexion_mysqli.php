<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blog acedemico";

// Crear conexión
$conexion = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conexion->connect_error) {
  die("Error al intentar conectarse a la base de datos: " . $conexion->connect_error);
}
//echo "Conexión OK";
