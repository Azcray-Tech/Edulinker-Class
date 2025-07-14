<?php
include(__DIR__ . "/../../lib/constants.php");
include(__DIR__ . "/../../lib/common.php");
include(__DIR__ . "/../../lib/books.php");

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $id_libro_eliminar = $_GET["id"];

    // Realizar la eliminación del libro
    $resultado = eliminarLibro($conexion, $id_libro_eliminar);

    if ($resultado) {
        // Redirigir con un mensaje de éxito
        header("Location:" . VIEWS_URL . "managers/gestor_libros.php?mensaje=libro_eliminado");
        exit();
    } else {
        // Redirigir con un mensaje de error
        header("Location:" . VIEWS_URL . "managers/gestor_libros.php?error=error_al_eliminar_libro");
        exit();
    }
} else {
    // Si no se proporciona un ID válido por GET, redirigir
    header("Location:" . VIEWS_URL . "managers/gestor_libros.php?error=id_libro_invalido");
    exit();
}

$conexion->close();
?>