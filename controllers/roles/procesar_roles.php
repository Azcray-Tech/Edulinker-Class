<?php
include(__DIR__ . "/../../lib/constants.php");
include(__DIR__ . "/../../lib/common.php");
include(__DIR__ . "/../../class/roleManager.php");

if (!isset($conexion)) {
    die("Error: No se pudo conectar a la base de datos.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = isset($_POST["user_id"]) ? filter_var($_POST["user_id"], FILTER_SANITIZE_NUMBER_INT) : null;
    $rol_id = isset($_POST["rol_id"]) ? filter_var($_POST["rol_id"], FILTER_SANITIZE_NUMBER_INT) : null;

    $resultado = RoleManager::actualizarRolUsuario($conexion, $user_id, $rol_id);

    if ($resultado["exito"]) {
        header("Location:" . VIEWS_URL . "managers/gestor_usuarios.php?mensaje=Rol actualizado correctamente&tipo=success");
        exit();
    } else {
        echo $resultado["error"];
        exit();
    }
} else {
    echo "Acceso no permitido.";
    exit();
}
?>