<?php
include_once(__DIR__ . "/../../lib/constants.php");
include_once(__DIR__ . "/../../lib/common.php");
include_once(__DIR__ . "/../../lib/session.php");
include_once(__DIR__ . "/../../lib/helpers.php"); 

// Verificar si el usuario tiene permiso de profesor (rol ID 2) o administrador (rol ID 1)
if (!verificarPermiso(2) && !verificarPermiso(1)) {
    header("Location: " . BASE_URL . "index.php");
    exit();
}

// Verificar si se ha enviado el formulario
if (isset($_POST['update_article'])) {
    
    $article_id = $_POST['article_id'];
    $title = $_POST['title'];
    $article_content = $_POST['article'];
    $category_id = $_POST['category']; // Este es el ID seleccionado en el formulario
    $user_id = $_SESSION["user_id"];

    // Obtener el nombre de la categoría basado en el ID
    $sql_nombre_categoria = "SELECT nombre FROM category WHERE id = ?";
    $stmt_nombre_categoria = mysqli_prepare($conexion, $sql_nombre_categoria);
    mysqli_stmt_bind_param($stmt_nombre_categoria, "i", $category_id);
    mysqli_stmt_execute($stmt_nombre_categoria);
    $result_nombre_categoria = mysqli_stmt_get_result($stmt_nombre_categoria);

    if ($row_nombre_categoria = mysqli_fetch_assoc($result_nombre_categoria)) {
        $category_name = $row_nombre_categoria['nombre']; // Este es el nombre que quieres guardar
    } else {
        // Manejar el caso en que no se encuentra la categoría
        echo "Categoría no encontrada.";
        exit();
    }

    mysqli_stmt_close($stmt_nombre_categoria);

    // Verificar si el artículo pertenece al usuario actual (intentar usar ArticleService)
    $article_user_id = null;
    try {
        if (!class_exists('\App\Article\ArticleService')) {
            if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
                require_once __DIR__ . '/../../vendor/autoload.php';
            }
        }
        if (class_exists('\App\Article\ArticleService')) {
            $service = new \App\Article\ArticleService();
            $art = $service->getArticleById($article_id);
            if ($art && isset($art['user'])) {
                $article_user_id = $art['user'];
            }
        }
    } catch (\Throwable $e) {
        error_log('ArticleService getArticleById error (update): ' . $e->getMessage());
    }

    // Fallback legacy si no se obtuvo con el servicio
    if ($article_user_id === null) {
        $sql_articulo = "SELECT user FROM articles WHERE id = ?";
        $stmt_articulo = mysqli_prepare($conexion, $sql_articulo);
        mysqli_stmt_bind_param($stmt_articulo, "i", $article_id);
        mysqli_stmt_execute($stmt_articulo);
        $result_articulo = mysqli_stmt_get_result($stmt_articulo);

        if ($row_articulo = mysqli_fetch_assoc($result_articulo)) {
            $article_user_id = $row_articulo['user'];
        } else {
            echo "Artículo no encontrado.";
            exit();
        }

        mysqli_stmt_close($stmt_articulo);
    }

    // DEBUGGING: Log session and article data in update_article.php
    error_log("UPDATE_DEBUG: User ID: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'N/A'));
    error_log("UPDATE_DEBUG: User Role: " . (isset($_SESSION['rol']) ? $_SESSION['rol'] : 'N/A'));
    error_log("UPDATE_DEBUG: Article Author ID: " . (isset($article_user_id) ? $article_user_id : 'N/A'));
    error_log("UPDATE_DEBUG: isAdmin() result: " . (isAdmin() ? 'true' : 'false'));

    // Allow editing if current user is the author OR an administrator/professor
    if ($article_user_id != $user_id && !isAdmin()) {
        echo "No tienes permiso para editar este artículo.";
        exit();
    }

    $image = null; // Inicializar la variable $image

    // Procesar la imagen de portada (opcional)
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = __DIR__ . "/../../uploads/articles/cover/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Verificar si el archivo es una imagen
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false && in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image = basename($_FILES["image"]["name"]); // Guardar solo el nombre del archivo
            } else {
                echo "Error al subir la imagen.";
                exit();
            }
        } else {
            echo "El archivo no es una imagen válida.";
            exit();
        }
    }
    // Usar ArticleService si está disponible, con fallback a la consulta legacy
    try {
        if (!class_exists('\App\Article\ArticleService')) {
            if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
                require_once __DIR__ . '/../../vendor/autoload.php';
            }
        }

        if (class_exists('\App\Article\ArticleService')) {
            $service = new \App\Article\ArticleService();
            $ok = $service->updateArticle($article_id, $title, $article_content, $image, $category_name);
        } else {
            // Fallback legacy
            if ($image !== null) {
                $sql_update = "UPDATE articles SET title = ?, article = ?, category = ?, image = ? WHERE id = ?";
                $stmt_update = mysqli_prepare($conexion, $sql_update);
                mysqli_stmt_bind_param($stmt_update, "ssssi", $title, $article_content, $category_name, $image, $article_id);
            } else {
                $sql_update = "UPDATE articles SET title = ?, article = ?, category = ? WHERE id = ?";
                $stmt_update = mysqli_prepare($conexion, $sql_update);
                mysqli_stmt_bind_param($stmt_update, "sssi", $title, $article_content, $category_name, $article_id);
            }
            $ok = mysqli_stmt_execute($stmt_update);
            mysqli_stmt_close($stmt_update);
        }

        if ($ok) {
            header("Location: " . VIEWS_URL . "articles/articles.php?id=" . $article_id);
            exit();
        } else {
            echo "Error al actualizar el artículo.";
        }
    } catch (\Throwable $e) {
        error_log('ArticleService update error: ' . $e->getMessage());
        echo "Error al actualizar el artículo: " . $e->getMessage();
    }
} else {
    // Si no se envió el formulario correctamente
    header("Location: " . VIEWS_URL . "articles/edit_article.php?id=" . $_POST['article_id']); // Redirigir de vuelta al formulario
    exit();
}

mysqli_close($conexion);
?>
