<?php
// eliminar_categoria.php
include_once(__DIR__ . "/../../lib/common.php");
include_once(__DIR__ . "/../../lib/constants.php");
include_once(__DIR__ . "/../../lib/helpers.php");

// Verificar si el usuario ha iniciado sesión y es administrador
if (!isset($_SESSION['user_id']) || !verificarPermiso(1)) {
    header("Location: " . BASE_URL . "index.php?error=permiso_denegado");
    exit();
}

// Verificar si se ha pasado el ID de la categoría a eliminar
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['mensaje'] = "ID de categoría inválido.";
    $_SESSION['tipo_mensaje'] = 'danger';
    header("Location: gestor_categorias.php");
    exit();
}

$categoria_id = $_GET['id'];

// Verificar si la categoría tiene artículos asociados antes de eliminar
// Obtener el nombre de la categoría (necesario tanto para el servicio como para el fallback)
$stmt_cat = $conexion->prepare("SELECT nombre FROM category WHERE id = ?");
$stmt_cat->bind_param("i", $categoria_id);
$stmt_cat->execute();
$res_cat = $stmt_cat->get_result();
$cat_row = $res_cat->fetch_assoc();
$stmt_cat->close();

$category_name = $cat_row['nombre'] ?? '';
$total_articulos = 0;

// Intentar usar el servicio de artículos para contar la cantidad por categoría (compatibilidad gradual)
try {
    if (!class_exists(\App\Article\ArticleService::class)) {
        if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
            require_once __DIR__ . '/../../vendor/autoload.php';
        }
    }
    if (class_exists(\App\Article\ArticleService::class) && $category_name !== '') {
        $service = new \App\Article\ArticleService();
        $total_articulos = $service->countArticlesByCategory($category_name);
    }
} catch (\Throwable $e) {
    error_log('ArticleService countArticlesByCategory error: ' . $e->getMessage());
}

// Fallback legacy si el servicio no devolvió el total
if ($total_articulos === 0) {
    $sql_verificar_articulos = "SELECT COUNT(*) AS total FROM articles WHERE category = ?";
    $stmt_verificar_articulos = $conexion->prepare($sql_verificar_articulos);
    // La columna 'category' almacena el nombre de la categoría en este esquema
    $stmt_verificar_articulos->bind_param("s", $category_name);
    $stmt_verificar_articulos->execute();
    $result_verificar_articulos = $stmt_verificar_articulos->get_result();
    $total_articulos = $result_verificar_articulos->fetch_assoc()['total'];
    $stmt_verificar_articulos->close();
}

if ($total_articulos > 0) {
    $_SESSION['mensaje'] = "No se puede eliminar la categoría porque tiene " . $total_articulos . " artículos asociados.";
    $_SESSION['tipo_mensaje'] = 'warning';
    header("Location: gestor_categorias.php");
    exit();
}

// Obtener el nombre de la imagen asociada para eliminar el archivo
$sql_obtener_imagen = "SELECT imagen FROM category WHERE id = ?";
$stmt_obtener_imagen = $conexion->prepare($sql_obtener_imagen);
$stmt_obtener_imagen->bind_param("i", $categoria_id);
$stmt_obtener_imagen->execute();
$result_obtener_imagen = $stmt_obtener_imagen->get_result();
$categoria = $result_obtener_imagen->fetch_assoc();
$stmt_obtener_imagen->close();

$imagen_a_eliminar = $categoria['imagen'];

// Eliminar la categoría de la base de datos
$sql_eliminar = "DELETE FROM category WHERE id = ?";
$stmt_eliminar = $conexion->prepare($sql_eliminar);
$stmt_eliminar->bind_param("i", $categoria_id);

if ($stmt_eliminar->execute()) {
    $_SESSION['mensaje'] = "Categoría eliminada correctamente.";
    $_SESSION['tipo_mensaje'] = 'success';
    // Eliminar el archivo de imagen si existe
    if (!empty($imagen_a_eliminar) && file_exists(UPLOADS_URL . "categories/cover/" . $imagen_a_eliminar)) {
        unlink(UPLOADS_URL . "categories/cover/" . $imagen_a_eliminar);
    }
} else {
    $_SESSION['mensaje'] = "Error al eliminar la categoría: " . $stmt_eliminar->error;
    $_SESSION['tipo_mensaje'] = 'danger';
}
$stmt_eliminar->close();

// Redirigir a la página de gestión de categorías
header("Location: gestor_categorias.php");
exit();
?>