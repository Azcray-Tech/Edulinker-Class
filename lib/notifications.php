<?php
/** Funciones relacionadas con notificaciones
 * Estas funciones permiten gestionar las notificaciones de los usuarios en la aplicación.
 */

/**
 * Obtiene las notificaciones no leídas de un usuario.
 * @param mysqli $conexion Conexión a la base de datos.
 * @param int $user_id ID del usuario.
 * @return array Lista de notificaciones no leídas.
 */
function obtenerNotificacionesNoLeidas($conexion, $user_id) {
    $notificaciones = [];
    if ($user_id) {
        $sql_notificaciones = "SELECT id, user_id, type, message, link, is_read, created_at, article_id FROM notifications WHERE user_id = ? AND is_read = 0 ORDER BY created_at DESC";
        $stmt_notificaciones = $conexion->prepare($sql_notificaciones);
        $stmt_notificaciones->bind_param("i", $user_id);
        $stmt_notificaciones->execute();
        $result_notificaciones = $stmt_notificaciones->get_result();
        $notificaciones = $result_notificaciones->fetch_all(MYSQLI_ASSOC);
        $stmt_notificaciones->close();
    }
    return $notificaciones;
}

/**
 * Marca una notificación como leída.
 * @param mysqli $conexion Conexión a la base de datos.
 * @param int $notificacion_id ID de la notificación.
 * @return bool True si se actualizó correctamente, false en caso contrario.
 */
function obtenerUltimasNotificaciones($conexion, $user_id, $limit = 10) {
    $todas_notificaciones = [];
    if ($user_id) {
        $sql_todas_notificaciones = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT ?";
        $stmt_todas_notificaciones = $conexion->prepare($sql_todas_notificaciones);
        $stmt_todas_notificaciones->bind_param("ii", $user_id, $limit);
        $stmt_todas_notificaciones->execute();
        $result_todas_notificaciones = $stmt_todas_notificaciones->get_result();
        $todas_notificaciones = $result_todas_notificaciones->fetch_all(MYSQLI_ASSOC);
        $stmt_todas_notificaciones->close();
    }
    return $todas_notificaciones;
}

/**
 * Marca una notificación como leída.
 * @param mysqli $conexion Conexión a la base de datos.
 * @param int $notification_id ID de la notificación.
 * @param int $user_id ID del usuario.
 * @return bool True si se actualizó correctamente, false en caso contrario.
 */
function marcarNotificacionComoLeida($conexion, $notification_id, $user_id) {
    if (isset($notification_id) && is_numeric($notification_id) && isset($user_id)) {
        $sql_update_notificacion = "UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?";
        $stmt_update_notificacion = $conexion->prepare($sql_update_notificacion);
        $stmt_update_notificacion->bind_param("ii", $notification_id, $user_id);
        $execution_result = $stmt_update_notificacion->execute(); // Primero ejecutamos
        $stmt_update_notificacion->close(); // Luego cerramos la sentencia
        return $execution_result; // Devolvemos el resultado de la ejecución

    }
    return false;
}


?>