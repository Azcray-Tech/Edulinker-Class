<?php 
define ("SEGUIR_LEYENDO", "Seguir leyendo...");

// Ruta base del proyecto (ajustada al nombre real de la carpeta)
define('BASE_URL', '/Edulinker-Class/');

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