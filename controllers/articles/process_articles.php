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
require_once __DIR__ . "/../../lib/helpers.php";
require_once __DIR__ . "/../../lib/user.php";
require_once __DIR__ . "/../../config/conexion_mysqli.php"; // For database connection
require_once __DIR__ . "/../../lib/categories.php"; // For category validation
// Intentar cargar autoload de Composer para usar ArticleService; si no existe, seguiremos con clase legacy
if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
    require_once __DIR__ . '/../../vendor/autoload.php';
}

// Verificar si el usuario tiene permisos para crear artículos (administrador o editor)
if (!verificarPermiso(1) && !verificarPermiso(2)) {
    header("Location:" . BASE_URL . "index.php?error=permiso_denegado");
    exit;
}

// Verificar si se han enviado los datos correctamente
if (!isset($_POST['title']) ||
    !isset($_POST['article']) ||
    !isset($_POST['category'])) {

    // Redirect with an error message if data is missing
    header("Location:" . BASE_URL . "views/articles/create_article.php?error=missing_data");
    exit;
}

$title = trim($_POST['title']);
$article = $_POST['article'];
$category = (int)$_POST['category']; // Cast to integer for validation
$user = $_SESSION['user_id'];
$image = "default.jpg"; // Imagen por defecto

$errors = [];

// Validate title
if (empty($title)) {
    $errors[] = "El título no puede estar vacío.";
} elseif (strlen($title) > 255) {
    $errors[] = "El título no puede exceder los 255 caracteres.";
}

// Validate category
$allCategories = obtenerCategorias($conexion); // Get all categories from the database
$validCategoryIds = array_column($allCategories, 'id');

if (!in_array($category, $validCategoryIds)) {
    $errors[] = "La categoría seleccionada no es válida.";
}

// Sanitize article content
$article = sanitizeArticleContent($article);

// If there are validation errors, redirect back to the form with errors
if (!empty($errors)) {
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_data'] = $_POST; // Preserve form data
    header("Location:" . BASE_URL . "views/articles/create_article.php?error=validation_failed");
    exit;
}

// Procesar la imagen de portada (opcional)
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $target_dir = __DIR__ . "/../../uploads/articles/cover/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Verificar si el archivo es una imagen
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check !== false && in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
        // Check file size (e.g., max 5MB)
        if ($_FILES["image"]["size"] > 5000000) {
            $errors[] = "La imagen es demasiado grande (máximo 5MB).";
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image = basename($_FILES["image"]["name"]); // Guardar solo el nombre del archivo
            } else {
                $errors[] = "Error al subir la imagen.";
            }
        }
    } else {
        $errors[] = "El archivo no es una imagen válida o el formato no es permitido (solo JPG, JPEG, PNG, GIF).";
    }

    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
        $_SESSION['form_data'] = $_POST; // Preserve form data
        header("Location:" . BASE_URL . "views/articles/create_article.php?error=image_upload_failed");
        exit;
    }
}

// Insertar el artículo usando el nuevo servicio si está disponible
try {
    if (class_exists('\App\Article\ArticleService')) {
        $service = new \App\Article\ArticleService();
        $ok = $service->createArticle($title, $article, $image, $category, $user);
    } else {
        // Fallback a la clase legacy
        $articles = new Articles(null, $title, $image, $article, null, $user, $category);
        $ok = $articles->insertarArticulo($conexion, $title, $article, $image, $category, $user);
    }

    if ($ok) {
        $_SESSION['success_message'] = "Artículo creado exitosamente.";
        header("Location:" . BASE_URL . "index.php");
        exit();
    } else {
        $errors[] = "Error al guardar el artículo en la base de datos.";
        $_SESSION['form_errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        header("Location:" . BASE_URL . "views/articles/create_article.php?error=db_save_failed");
        exit;
    }
} catch (\Throwable $e) {
    error_log('Error al crear artículo: ' . $e->getMessage());
    $errors[] = "Error interno al guardar el artículo: " . $e->getMessage();
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_data'] = $_POST;
    header("Location:" . BASE_URL . "views/articles/create_article.php?error=exception");
    exit;
} finally {
    if (isset($conexion) && $conexion instanceof mysqli) {
        mysqli_close($conexion);
    }
}
?>
