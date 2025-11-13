<?php
include_once(__DIR__ . "/../../lib/common.php");
include_once(__DIR__ . "/../../lib/constants.php");
include_once(__DIR__ . "/../../class/comments.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: " . VIEWS_URL . "auth/login.php");
    exit();
}

if (!isset($_POST['id_comentario']) || !is_numeric($_POST['id_comentario'])) {
    header("Location: " . VIEWS_URL . "articles/articles.php?error_edicion=" . urlencode("ID de comentario inválido."));
    exit();
}

$comentario_id = $_POST['id_comentario'];
$nuevo_contenido = $_POST['contenido_comentario'] ?? '';
$usuario_id = $_SESSION['user_id'];

$resultado = comments::editarComentario($conexion, $comentario_id, $nuevo_contenido, $usuario_id);

if ($resultado['exito']) {
    $articulo_id = $resultado['articulo_id'];
    header("Location: " . VIEWS_URL . "articles/articles.php?id=" . urlencode($articulo_id) . "&comentario_editado=true");
    exit();
} else {
    // Si hay error, redirige con el mensaje
    $articulo_id = $resultado['articulo_id'] ?? '';
    header("Location: " . VIEWS_URL . "articles/articles.php?id=" . urlencode($articulo_id) . "&error_edicion=" . urlencode($resultado['error']));
    exit();
}
?>