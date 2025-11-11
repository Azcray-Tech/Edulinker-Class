<?php
// Copia reubicada: controllers/articles/process_articles.php -> app/Controllers/articles/process_articles.php
require_once __DIR__ . "/../../../lib/common.php";
require_once __DIR__ . "/../../../lib/helpers.php";
require_once __DIR__ . "/../../../lib/user.php";
if (file_exists(__DIR__ . '/../../../vendor/autoload.php')) {
    require_once __DIR__ . '/../../../vendor/autoload.php';
}

if (!verificarPermiso(1) && !verificarPermiso(2)) {
    header("Location:" . BASE_URL . "index.php?error=permiso_denegado");
    exit;
}

if (!isset($_POST['title']) || !isset($_POST['article']) || !isset($_POST['category'])) {
    echo "No se han enviado los datos correctamente";
    die();
}

$title = $_POST['title'];
$article = $_POST['article'];
$category = $_POST['category'];
$user = $_SESSION['user_id'];
$image = "default.jpg";

if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $target_dir = __DIR__ . "/../../../uploads/articles/cover/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check !== false && in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = basename($_FILES["image"]["name"]);
        } else {
            echo "Error al subir la imagen.";
        }
    } else {
        echo "El archivo no es una imagen válida.";
    }
}

try {
    if (class_exists('\App\Article\ArticleService')) {
        $service = new \App\Article\ArticleService();
        $ok = $service->createArticle($title, $article, $image, $category, $user);
    } else {
        $articles = new Articles(null, $title, $image, $article, null, $user, $category);
        $ok = $articles->insertarArticulo($conexion, $title, $article, $image, $category, $user);
    }

    if ($ok) {
        header("Location:" . BASE_URL . "index.php");
        exit();
    } else {
        echo "Error al guardar el artículo.";
    }
} catch (\Throwable $e) {
    error_log('Error al crear artículo: ' . $e->getMessage());
    echo "Error al guardar el artículo: " . $e->getMessage();
}

mysqli_close($conexion);
?>
