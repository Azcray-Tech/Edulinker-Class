<?php
include_once(__DIR__ . "/../../lib/constants.php");
include_once(__DIR__ . "/../../config/conexion_mysqli.php");
include_once(__DIR__ . "/../../class/users.php");

if (!isset($_POST['username']) || !isset($_POST['email']) || !isset($_POST['password'])) {
    echo "No se han enviado los datos correctamente";
    die();
}

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$rol_id = null;
$imagen_pre = "NoFoto.png";

$resultado = Users::registrarUsuario($conexion, $username, $email, $password, $rol_id, $imagen_pre);

if ($resultado["exito"]) {
    header("Location: " . BASE_URL . "views/auth/login.php");
    exit;
} else {
    echo $resultado["error"];
}
?>