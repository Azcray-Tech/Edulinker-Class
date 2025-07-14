<?php

/**
 * clase para gestionar los comentarios en los artículos.
 * Esta clase incluye métodos para validar, insertar y notificar nuevos comentarios.
 */
class Comments
{
    public static function validarComentario($contenido)
    {
        return !empty(trim($contenido));
    }

    public static function insertarComentario($conexion, $articulo_id, $usuario_id, $contenido)
    {
        $sql = "INSERT INTO comentarios (articulo_id, usuario_id, contenido, fecha_creacion) VALUES (?, ?, ?, NOW())";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("iis", $articulo_id, $usuario_id, $contenido);
        return $stmt->execute();
    }

    public static function notificarNuevoComentario($conexion, $articulo_id, $usuario_id)
    {
        // Aquí puedes poner la lógica de notificación (correo, alerta, etc.)
        // Por ahora solo un ejemplo vacío
        return true;
    }

    /**
     * Obtiene la información de un comentario específico de la base de datos.
     * @param mysqli $conexion La conexión a la base de datos MySQLi.
     * @param int $comentario_id El ID del comentario que se desea obtener.
     * @return array|null Un array asociativo con la información del comentario si se encuentra,
     * o null si no se encuentra o si ocurre un error.
     */
    public static function obtenerComentario($conexion, $comentario_id)
    {
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

    public static function editarComentario($conexion, $comentario_id, $nuevo_contenido, $usuario_id)
    {
        // Obtener el comentario para verificar el autor
        $comentario = self::obtenerComentario($conexion, $comentario_id);
        if (!$comentario) {
            return ["exito" => false, "error" => "Comentario no encontrado."];
        }
        if ($comentario['usuario_id'] != $usuario_id) {
            return ["exito" => false, "error" => "No tienes permiso para editar este comentario."];
        }
        if (empty(trim($nuevo_contenido))) {
            return ["exito" => false, "error" => "El contenido del comentario no puede estar vacío."];
        }

        $sql = "UPDATE comentarios SET contenido = ? WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("si", $nuevo_contenido, $comentario_id);
            if ($stmt->execute()) {
                $stmt->close();
                return ["exito" => true, "articulo_id" => $comentario['articulo_id']];
            } else {
                $error = $stmt->error;
                $stmt->close();
                return ["exito" => false, "error" => "Error al actualizar el comentario: $error"];
            }
        } else {
            return ["exito" => false, "error" => "Error al preparar la consulta de actualización."];
        }
    }
}
