<?php

/**
 * Script para procesar el envío de comentarios en un artículo.
 *
 * Este script recibe datos de un formulario enviado por método POST,
 * valida la información del comentario y, si es válida, la inserta
 * en la base de datos y notifica al autor del artículo.
 */

// Inclusión de archivos necesarios
include(__DIR__ . "/../../lib/common.php");     // Funciones comunes y configuración
include(__DIR__ . "/../../lib/constants.php"); // Definición de constantes (como URLs)
include(__DIR__ . "/../../class/comments.php");   // Clase para la gestión de comentarios
include_once(__DIR__ . "/../../lib/helpers.php"); // Funciones de ayuda


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['user_id']) && isset($_POST['contenido_comentario'])) {
        $usuario_id = $_SESSION['user_id'];
        $contenido = $_POST['contenido_comentario'];

        // Verificar si se proporcionó un ID de artículo válido mediante GET
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $articulo_id = $_GET['id'];

            
            if (comments::validarComentario($contenido)) {
                $contenido_seguro = $conexion->real_escape_string($contenido);

                if (comments::insertarComentario($conexion, $articulo_id, $usuario_id, $contenido_seguro)) {
                    
                    comments::notificarNuevoComentario($conexion, $articulo_id, $usuario_id);

                    header("Location:" . VIEWS_URL . "articles/articles.php?id=" . urlencode($articulo_id));
                    exit();
                } else {
                    echo "Error al guardar el comentario: " . $conexion->error;
                }
            } else {
                echo "El comentario no puede estar vacío.";
            }
        } else {
            echo "Error: ID de artículo no válido.";
        }
    } else {
        echo "Error: Debes iniciar sesión para comentar.";
    }

    $conexion->close();
} else {
    header("HTTP/1.1 403 Forbidden");
    echo "Acceso prohibido.";
}

?>