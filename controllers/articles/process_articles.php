<?php
/**
 * Script para procesar la creación de un nuevo artículo.
 *
 * Recibe los datos del formulario de 'crear_articulo.php',
 * valida la información, guarda la imagen de portada (si se proporciona),
 * y guarda el título, contenido, categoría, usuario y ruta de la imagen en la base de datos.
 */

// Incluir los archivos necesarios
require_once __DIR__ . "/../../lib/common.php";
require_once __DIR__ . "/../../class/articles.php";
require_once __DIR__ . "/../../lib/helpers.php";
require_once __DIR__ . "/../../lib/user.php";

// Verificar si el usuario tiene permisos para crear artículos (administrador o editor)
if (!verificarPermiso(1) && !verificarPermiso(2)) {
    header("Location:" . BASE_URL . "index.php?error=permiso_denegado");
    exit;
}

// Verificar si se han enviado los datos correctamente
if (!isset($_POST['title']) ||
    !isset($_POST['article']) ||
    !isset($_POST['category'])) {

    echo "No se han enviado los datos correctamente";
    die();
}

$title = $_POST['title'];
$article = $_POST['article'];
$category = $_POST['category'];
$user = $_SESSION['user_id'];
$image = "default.jpg"; // Imagen por defecto

// Procesar la imagen de portada (opcional)
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $target_dir = __DIR__ . "/../../uploads/articles/cover/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Verificar si el archivo es una imagen
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check !== false && in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = basename($_FILES["image"]["name"]); // Guardar solo el nombre del archivo
        } else {
            echo "Error al subir la imagen.";
        }
    } else {
        echo "El archivo no es una imagen válida.";
    }
}

$articles = new Articles(null, $title, $image, $article, null, $user, $category);

// Insertar el artículo en la base de datos
if ($articles->insertarArticulo($conexion, $title, $article, $image, $category, $user)) {
    header("Location:" . BASE_URL . "index.php");
    exit();
} else {
    echo "Error al guardar el artículo: " . mysqli_error($conexion);
}

mysqli_close($conexion);
?>
