<?php
session_start();
include_once(__DIR__ . "/../../lib/constants.php");
include_once(__DIR__ . "/../../config/conexion_mysqli.php");
include_once(__DIR__ . "/../../class/users.php");

if (!isset($_POST['email']) || !isset($_POST['password'])) {
    echo "No se han enviado los datos correctamente";
    die();
}

$email = $_POST['email'];
$password = $_POST['password'];

$resultado = Users::login($conexion, $email, $password);

if ($resultado["exito"]) {
    $_SESSION['user_id'] = $resultado["user"]["id"];
    $_SESSION['username'] = $resultado["user"]["username"];
    $_SESSION['email'] = $resultado["user"]["email"];
    $_SESSION['imagen'] = $resultado["user"]["imagen"];
    $_SESSION['rol_id'] = $resultado["user"]["rol_id"];
    $_SESSION['rol'] = $resultado["user"]["rol"];
    header("Location: " . BASE_URL . "index.php");
    exit();
} else {
    $_SESSION['login_error'] = $resultado["error"];
    header("Location: " . BASE_URL . "views/auth/login.php");
    exit();
}
?>