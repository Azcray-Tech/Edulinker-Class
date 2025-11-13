<?php
// procesar_editar_categoria.php
include_once(__DIR__ . "/../../lib/common.php");
include_once(__DIR__ . "/../../lib/constants.php");
include_once(__DIR__ . "/../../lib/helpers.php");

// Verificar si el usuario ha iniciado sesión y es administrador
if (!isset($_SESSION['user_id']) || !verificarPermiso(1)) {
    header("Location: " . BASE_URL . "index.php?error=permiso_denegado");
    exit();
}

// Verificar si se ha enviado el formulario por POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validar los datos del formulario
    if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
        $_SESSION['mensaje'] = "ID de categoría inválido.";
        $_SESSION['tipo_mensaje'] = 'danger';
        header("Location: gestor_categorias.php");
        exit();
    }
    if (empty($_POST['nombre'])) {
        $_SESSION['mensaje'] = "El nombre de la categoría es obligatorio.";
        $_SESSION['tipo_mensaje'] = 'danger';
        header("Location: editar_categoria.php?id=" . $_POST['id']);
        exit();
    }

    $categoria_id = $conexion->real_escape_string($_POST['id']);
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $text = isset($_POST['text']) ? $conexion->real_escape_string($_POST['text']) : '';
    $imagen_anterior = $conexion->real_escape_string($_POST['imagen_anterior']);
    $imagen_nueva = '';

    // Procesar la subida de la nueva imagen si se proporciona
    if (!empty($_FILES["imagen"]["name"])) {
        $nombreArchivo = basename($_FILES["imagen"]["name"]);
        $rutaDestino = UPLOADS_URL . "categories/cover/" . $nombreArchivo;
        $tipoArchivo = strtolower(pathinfo($rutaDestino, PATHINFO_EXTENSION));
        $formatosPermitidos = array("jpg", "jpeg", "png", "gif");

        if (!in_array($tipoArchivo, $formatosPermitidos)) {
            $_SESSION['mensaje'] = "Formato de imagen no válido. Solo se permiten JPG, JPEG, PNG y GIF.";
            $_SESSION['tipo_mensaje'] = 'warning';
            header("Location: editar_categoria.php?id=" . $categoria_id);
            exit();
        }

        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaDestino)) {
            $imagen_nueva = $nombreArchivo;
            // Eliminar la imagen anterior si se subió una nueva
            if (!empty($imagen_anterior) && file_exists(UPLOADS_URL . "categories/cover/" . $imagen_anterior)) {
                unlink(UPLOADS_URL . "categories/cover/" . $imagen_anterior);
            }
        } else {
            $_SESSION['mensaje'] = "Error al subir la nueva imagen.";
            $_SESSION['tipo_mensaje'] = 'danger';
            header("Location: editar_categoria.php?id=" . $categoria_id);
            exit();
        }
    } else {
        $imagen_nueva = $imagen_anterior; // Mantener la imagen anterior si no se sube una nueva
    }

    // Actualizar la información de la categoría en la base de datos
    $sql = "UPDATE category SET nombre = ?, imagen = ?, text = ? WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssi", $nombre, $imagen_nueva, $text, $categoria_id);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Categoría actualizada correctamente.";
        $_SESSION['tipo_mensaje'] = 'success';
    } else {
        $_SESSION['mensaje'] = "Error al actualizar la categoría: " . $stmt->error;
        $_SESSION['tipo_mensaje'] = 'danger';
    }
    $stmt->close();

    // Redirigir a la página de gestión de categorías
    header("Location: gestor_categorias.php");
    exit();

} else {
    // Si se intenta acceder a este archivo por GET, redirigir
    header("Location: gestor_categorias.php");
    exit();
}
?>