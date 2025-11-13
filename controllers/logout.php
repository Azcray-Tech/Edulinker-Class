<?php 
include_once(__DIR__ . "/../lib/constants.php");
include_once(__DIR__ . "/../lib/common.php");

$_SESSION = array();
session_destroy();

// Redirigir al archivo de inicio de sesión
header("Location: " . VIEWS_URL . "auth/login.php");
exit();
?>