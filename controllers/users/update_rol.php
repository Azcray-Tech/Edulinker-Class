<?php
include_once(__DIR__ . "/../../config/conexion_mysqli.php");

if (isset($_POST['usuarioId']) && isset($_POST['rol'])) {
    $usuarioId = $_POST['usuarioId'];
    $rol = $_POST['rol'];

    $sql = "UPDATE users SET rol = ? WHERE id = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "si", $rol, $usuarioId);

    if (mysqli_stmt_execute($stmt)) {
        echo "Rol actualizado correctamente";
    } else {
        echo "Error al actualizar el rol";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conexion);
} else {
    echo "No se han enviado los datos correctamente";
}
?>
