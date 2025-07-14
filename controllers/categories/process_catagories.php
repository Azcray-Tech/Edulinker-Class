<?php
// procesar_categoria.php (para agregar una nueva categoría)
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
    if (empty($_POST['nombre'])) {
        $_SESSION['mensaje'] = "El nombre de la categoría es obligatorio.";
        $_SESSION['tipo_mensaje'] = 'danger';
        header("Location:" . VIEWS_URL . "managers/gestor_categorias.php");
        exit();
    }

    $nombre = $conexion->real_escape_string($_POST["nombre"]);
    $text = isset($_POST["text"]) ? $conexion->real_escape_string($_POST["text"]) : '';
    $imagen = ''; // Inicializar la variable de la imagen

    // Procesar la subida de la imagen si se proporciona
    if (!empty($_FILES["imagen"]["name"])) {
        $nombreArchivo = basename($_FILES["imagen"]["name"]);
        $rutaDestino = UPLOADS_URL . "categories/cover/" . $nombreArchivo;
        $tipoArchivo = strtolower(pathinfo($rutaDestino, PATHINFO_EXTENSION));

        // Validar el tipo de archivo
        $formatosPermitidos = array("jpg", "jpeg", "png", "gif");
        if (!in_array($tipoArchivo, $formatosPermitidos)) {
            $_SESSION['mensaje'] = "Formato de imagen no válido. Solo se permiten JPG, JPEG, PNG y GIF.";
            $_SESSION['tipo_mensaje'] = 'warning';
            header("Location:" . VIEWS_URL . "managers/gestor_categorias.php");
            exit();
        }

        // Mover el archivo subido al directorio de destino
        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaDestino)) {
            $imagen = $nombreArchivo;
        } else {
            $_SESSION['mensaje'] = "Error al subir la imagen.";
            $_SESSION['tipo_mensaje'] = 'danger';
            header("Location:" . VIEWS_URL . "managers/gestor_categorias.php");
            exit();
        }
    }

    // Insertar la nueva categoría en la base de datos
    $sql = "INSERT INTO category (nombre, imagen, text) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sss", $nombre, $imagen, $text);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Categoría agregada con éxito.";
        $_SESSION['tipo_mensaje'] = 'success';
    } else {
        $_SESSION['mensaje'] = "Error al agregar la categoría: " . $stmt->error;
        $_SESSION['tipo_mensaje'] = 'danger';
    }
    $stmt->close();

    // Redirigir a la página de gestión de categorías
    header("Location:" . VIEWS_URL . "managers/gestor_categorias.php");
    exit();

} else {
    // Si se intenta acceder a este archivo por GET, redirigir
    header("Location:" . VIEWS_URL . "managers/gestor_categorias.php");
    exit();
}
?>