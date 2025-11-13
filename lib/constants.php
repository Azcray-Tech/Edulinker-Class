<?php 
define ("SEGUIR_LEYENDO", "Seguir leyendo...");

// Obtener la base URL desde la variable de entorno o usar valor por defecto

$envBase = getenv('APP_BASE_URL');
if ($envBase !== false && $envBase !== '') {
    define('BASE_URL', rtrim($envBase, '/') . '/');
} else {
    define('BASE_URL', '/Edulinker-Class/');
}

// Rutas específicas
define('ASSETS_URL', BASE_URL . 'assets/');
define('UPLOADS_URL', BASE_URL . 'uploads/');
define('LIB_URL', BASE_URL . 'lib/');
define('INCLUDES_URL', BASE_URL . 'includes/');
define('VIEWS_URL', BASE_URL . 'views/');
define('CONTROLLERS_URL', BASE_URL . 'controllers/');

define('LIB_PATH', __DIR__ . '/../lib/');
// Rutas para estructura MVC (app/)
define('APP_URL', BASE_URL . 'app/');
define('APP_CONTROLLERS_URL', APP_URL . 'Controllers/');
define('APP_VIEWS_URL', APP_URL . 'Views/');
define('APP_MODELS_URL', APP_URL . 'Models/');

define('APP_PATH', __DIR__ . '/../app/');
?>
