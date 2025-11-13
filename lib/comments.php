<?php
/**
 * Obtiene la información de un comentario específico de la base de datos.
 * @param mysqli $conexion La conexión a la base de datos MySQLi.
 * @param int $comentario_id El ID del comentario que se desea obtener.
 * @return array|null Un array asociativo con la información del comentario si se encuentra,
 * o null si no se encuentra o si ocurre un error.
 */
function obtenerComentario($conexion, $comentario_id) {
    $comentario_id = $conexion->real_escape_string($comentario_id);

    $sql = "SELECT id, articulo_id, usuario_id, contenido, fecha_creacion FROM comentarios WHERE id = ?";
    $stmt = $conexion->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $comentario_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows == 1) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
        $stmt->close();
    } else {
        return null;
    }
}

/**
 * Inserta una notificación en la base de datos.
 *
 * @param mysqli $conexion La conexión a la base de datos MySQLi.
 * @param int $user_id El ID del usuario al que se le enviará la notificación.
 * @param string $type El tipo de notificación (ej. "comentario", "me gusta", etc.).
 * @param string $message El mensaje de la notificación.
 * @param string|null $link Un enlace opcional relacionado con la notificación (actualmente no lo usaremos para el ID del artículo).
 * @param int|null $article_id El ID del artículo relacionado con la notificación.
 */
function insertarNotificacion($conexion, $user_id, $type, $message, $link = null, $article_id = null) {
    $sql_insert_notificacion = "INSERT INTO notifications (user_id, type, message, link, article_id) VALUES (?, ?, ?, ?, ?)";
    $stmt_insert_notificacion = $conexion->prepare($sql_insert_notificacion);
    $stmt_insert_notificacion->bind_param("ssssi", $user_id, $type, $message, $link, $article_id);
    $stmt_insert_notificacion->execute();
    $stmt_insert_notificacion->close();
}

/**
 * Valida el contenido de un comentario.
 * @param string $contenido El contenido del comentario.
 * @return bool True si el contenido es válido, false en caso contrario.
 */
function validarComentario($contenido) {
    $contenido = trim($contenido);
    return !empty($contenido);
}

/**
 * Inserta un nuevo comentario en la base de datos.
 * @param mysqli $conexion La conexión a la base de datos MySQLi.
 * @param int $articulo_id El ID del artículo al que se le está comentando.
 * @param int $usuario_id El ID del usuario que está comentando.
 * @param string $contenido El contenido del comentario.
 * @return bool True si el comentario se insertó correctamente, false en caso contrario.
 */
function insertarComentario($conexion, $articulo_id, $usuario_id, $contenido) {
    $sql_insert = "INSERT INTO comentarios (articulo_id, usuario_id, contenido, fecha_creacion) VALUES (?, ?, ?, NOW())";
    $stmt_insert = $conexion->prepare($sql_insert);
    $stmt_insert->bind_param("iis", $articulo_id, $usuario_id, $contenido);
    $resultado = $stmt_insert->execute();
    $stmt_insert->close();
    return $resultado;
}

/**
 * Notifica al autor del artículo sobre un nuevo comentario.
 * @param mysqli $conexion La conexión a la base de datos MySQLi.
 * @param int $articulo_id El ID del artículo al que se le ha comentado.
 * @param int $comentarista_id El ID del usuario que ha comentado.
 * @return void devuelve nada.
 * Esta función inserta una notificación en la base de datos para el autor del artículo,
 * informándole que alguien ha comentado en su artículo.
 * Si el comentarista es el mismo que el autor, no se envía la notificación.
 */
function notificarNuevoComentario($conexion, $articulo_id, $comentarista_id) {
    $sql_autor = "SELECT user, title FROM articles WHERE id = ?";
    $stmt_autor = $conexion->prepare($sql_autor);
    $stmt_autor->bind_param("i", $articulo_id);
    $stmt_autor->execute();
    $result_autor = $stmt_autor->get_result();

    if ($row_autor = $result_autor->fetch_assoc()) {
        $autor_id = $row_autor['user'];
        $titulo_articulo = $row_autor['title'];

        if ($autor_id != $comentarista_id) {
            $mensaje_notificacion = "Alguien ha comentado en tu artículo '" . htmlspecialchars($titulo_articulo) . "'.";
            $link_notificacion = VIEWS_URL . "articles/articles.php?id=" . urlencode($articulo_id) . "#comentarios";
            // Modifica la llamada a insertarNotificacion para incluir $articulo_id
            insertarNotificacion($conexion, $autor_id, 'nuevo_comentario', $mensaje_notificacion, $link_notificacion, $articulo_id);
        }
    }
    $stmt_autor->close();
}





?>
