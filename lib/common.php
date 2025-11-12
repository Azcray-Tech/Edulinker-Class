<?php
session_start();
include(__DIR__ . "/../config/conexion_mysqli.php");
date_default_timezone_set('America/Caracas');

global $conexion;
// `config/conexion_mysqli.php` intenta crear $conexion usando variables de entorno.
// Evitar sobrescribir y evitar uso de variables legacy indefinidas ($servername, etc.).
if (!isset($conexion) || !($conexion instanceof mysqli)) {
    // Fallback seguro: crear conexión desde variables de entorno o valores por defecto.
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $dbHost = getenv('APP_DB_HOST') !== false && getenv('APP_DB_HOST') !== '' ? getenv('APP_DB_HOST') : 'localhost';
    $dbUser = getenv('APP_DB_USER') !== false && getenv('APP_DB_USER') !== '' ? getenv('APP_DB_USER') : 'root';
    $dbPass = getenv('APP_DB_PASS') !== false && getenv('APP_DB_PASS') !== '' ? getenv('APP_DB_PASS') : '';
    $dbName = getenv('APP_DB_NAME') !== false && getenv('APP_DB_NAME') !== '' ? getenv('APP_DB_NAME') : 'edulinker';

    try {
        $conexion = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    } catch (mysqli_sql_exception $e) {
        http_response_code(500);
        echo "Fatal error: could not connect to database ({$dbHost}): " . htmlspecialchars($e->getMessage());
        exit;
    }
}

if (!function_exists('cortarTexto')) {
    function cortarTexto($text, $chart = 450){
        $text = $text . " ";
        $text = substr($text, 0, $chart);
        $text = substr($text, 0, strrpos($text, ' '));
        $text = $text . "...";
        return $text;
    }
}

function isAdmin() {
    if (isset($_SESSION['rol']) && ($_SESSION['rol'] == 'admin' || $_SESSION['rol'] == 'profesor' || $_SESSION['rol'] == 'administrador')) {
        return true;
    }
    return false;
}

function requireAdmin() {
    if (!isAdmin()) {
        header("Location:" . BASE_URL . "index.php");
        exit();
    }
}

function obtenerArticulo($conexion, $id_post) {
    $sql = "SELECT
            a.*,
            u.username
        FROM
            articles a
        JOIN
            users u ON a.user = u.id
        WHERE
            a.id = ?";
    $stmt = mysqli_prepare($conexion, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id_post);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result) {
            $articulo = mysqli_fetch_assoc($result);
            mysqli_free_result($result);
            mysqli_stmt_close($stmt);
            return $articulo;
        } else {
            
            error_log("Error en la consulta obtenerArticulo: " . mysqli_error($conexion));
            mysqli_stmt_close($stmt);
            return null;
        }
    } else {
        
        error_log("Error al preparar la consulta obtenerArticulo: " . mysqli_error($conexion));
        return null;
    }
}

/*function obtenerCategorias($conexion) {
    $sql = "SELECT nombre FROM category ORDER BY nombre ASC";
    $result = mysqli_query($conexion, $sql);

    if ($result) {
        $categorias = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $categorias[] = $row['nombre'];
        }
        mysqli_free_result($result); // Liberar el resultado
        return $categorias;
    } else {
        // Manejar errores de consulta
        error_log("Error en la consulta obtenerCategorias: " . mysqli_error($conexion));
        return null;
    }
}
    */

// Función para generar la paginación
function generarPaginacion($paginaActual, $totalPaginas, $urlBase) {
    $paginacion = '<nav aria-label="Paginación"><ul class="pagination justify-content-center">';

    if ($paginaActual > 1) {
        $paginacion .= '<li class="page-item"><a class="page-link" href="' . $urlBase . '?pagina=' . ($paginaActual - 1) . '">Anterior</a></li>';
    }

    for ($i = 1; $i <= $totalPaginas; $i++) {
        $activeClass = ($i == $paginaActual) ? 'active' : '';
        $paginacion .= '<li class="page-item ' . $activeClass . '"><a class="page-link" href="' . $urlBase . '?pagina=' . $i . '">' . $i . '</a></li>';
    }

    if ($paginaActual < $totalPaginas) {
        $paginacion .= '<li class="page-item"><a class="page-link" href="' . $urlBase . '?pagina=' . ($paginaActual + 1) . '">Siguiente</a></li>';
    }

    $paginacion .= '</ul></nav>';
    return $paginacion;
}
?>
