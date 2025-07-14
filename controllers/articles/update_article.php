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

    // Verificar si el artículo pertenece al usuario actual (esta lógica se mantiene)
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

    if ($article_user_id != $user_id) {
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

    // Preparar la consulta para actualizar el artículo
    if ($image !== null) {
        // Si se subió una nueva imagen, actualizar el campo 'image'
        $sql_update = "UPDATE articles SET title = ?, article = ?, category = ?, image = ? WHERE id = ?";
        $stmt_update = mysqli_prepare($conexion, $sql_update);
        mysqli_stmt_bind_param($stmt_update, "ssssi", $title, $article_content, $category_name, $image, $article_id);
    } else {
        // Si no se subió una nueva imagen, no actualizar el campo 'image'
        $sql_update = "UPDATE articles SET title = ?, article = ?, category = ? WHERE id = ?";
        $stmt_update = mysqli_prepare($conexion, $sql_update);
        mysqli_stmt_bind_param($stmt_update, "sssi", $title, $article_content, $category_name, $article_id);
    }

    if (mysqli_stmt_execute($stmt_update)) {
        // Éxito al actualizar el artículo
        header("Location: " . VIEWS_URL . "articles/articles.php?id=" . $article_id); // Redirigir a la página del artículo
        exit();
    } else {
        // Error al actualizar el artículo
        echo "Error al actualizar el artículo: " . mysqli_error($conexion);
    }

    mysqli_stmt_close($stmt_update);
} else {
    // Si no se envió el formulario correctamente
    header("Location: " . VIEWS_URL . "articles/edit_article.php?id=" . $_POST['article_id']); // Redirigir de vuelta al formulario
    exit();
}

mysqli_close($conexion);
?>
