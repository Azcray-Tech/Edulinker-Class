<?php
// Script rápido para verificar que las constantes de URL y las rutas necesarias existen
chdir(__DIR__ . '/..'); // situarnos en la raíz del proyecto

// Cargar constantes (no fatal si falla)
if (file_exists(__DIR__ . '/../lib/constants.php')) {
    require_once __DIR__ . '/../lib/constants.php';
} else {
    echo "No se encontró lib/constants.php\n";
    exit(1);
}

$root = realpath(__DIR__ . '/..');
echo "Proyecto root: $root\n\n";

$checks = [
    'BASE_URL' => defined('BASE_URL') ? BASE_URL : null,
    'VIEWS_URL' => defined('VIEWS_URL') ? VIEWS_URL : null,
    'ASSETS_URL' => defined('ASSETS_URL') ? ASSETS_URL : null,
    'UPLOADS_URL' => defined('UPLOADS_URL') ? UPLOADS_URL : null,
    'LIB_URL' => defined('LIB_URL') ? LIB_URL : null,
    'INCLUDES_URL' => defined('INCLUDES_URL') ? INCLUDES_URL : null,
    'CONTROLLERS_URL' => defined('CONTROLLERS_URL') ? CONTROLLERS_URL : null,
    'LIB_PATH' => defined('LIB_PATH') ? LIB_PATH : null,
];

// Función para normalizar URL a path relativo
function url_to_path($url) {
    // Quitar starting slash y trailing slash
    $u = trim($url, '/');
    if ($u === '') return '';
    // Si el primer segmento coincide con el nombre de la carpeta del proyecto,
    // lo eliminamos para mapear correctamente a la raíz del proyecto.
    $segments = explode('/', $u);
    $projectBasename = basename(realpath(__DIR__ . '/..'));
    if (count($segments) > 0 && $segments[0] === $projectBasename) {
        array_shift($segments);
        $u = implode('/', $segments);
    }
    return $u === '' ? '' : str_replace('/', DIRECTORY_SEPARATOR, $u);
}

$results = [];
foreach ($checks as $name => $val) {
    if ($val === null) {
        $results[$name] = ['ok' => false, 'message' => 'Constante no definida'];
        continue;
    }

    // Para LIB_PATH usamos la ruta tal cual
    if ($name === 'LIB_PATH') {
        $path = realpath($val);
        $results[$name] = ['ok' => $path !== false, 'path' => $path ?: $val];
        continue;
    }

    // Comprobar que la constante no contiene espacios (común problema)
    if (strpos($val, ' ') !== false) {
        $results[$name] = ['ok' => false, 'message' => 'Contiene espacios: ' . $val];
        continue;
    }

    // Convertir URL a path relativo y comprobar existencia
    $rel = url_to_path($val);
    if ($rel === '') {
        // raíz del host
        $results[$name] = ['ok' => true, 'path' => $root];
        continue;
    }

    $candidate = $root . DIRECTORY_SEPARATOR . $rel;
    $real = realpath($candidate);
    $results[$name] = ['ok' => $real !== false, 'path' => $real ?: $candidate];
}

// Mostrar resultados
echo "Verificación de constantes y rutas:\n";
foreach ($results as $k => $r) {
    if ($r['ok']) {
        echo "[OK]  $k -> " . ($r['path'] ?? '') . "\n";
    } else {
        $msg = isset($r['message']) ? $r['message'] : 'No existe: ' . ($r['path'] ?? '');
        echo "[ERR] $k -> $msg\n";
    }
}

// Revisar si hay archivos principales faltantes (index.php)
echo "\nComprobaciones adicionales:\n";
$mainIndex = $root . DIRECTORY_SEPARATOR . 'index.php';
echo (file_exists($mainIndex) ? "[OK] index.php encontrado\n" : "[ERR] index.php NO encontrado\n");

exit(0);
