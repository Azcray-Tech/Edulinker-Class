<?php

/**
 * Clase para gestionar los roles de los usuarios.
 * Esta clase incluye métodos para actualizar el rol de un usuario.
 */

class RoleManager
{
    /**
     * Actualiza el rol de un usuario.
     * @param mysqli $conexion
     * @param int $user_id
     * @param int|null $rol_id
     * @return array Resultado de la operación
     */
    public static function actualizarRolUsuario($conexion, $user_id, $rol_id)
    {
        if ($user_id === null || $user_id <= 0) {
            return ["exito" => false, "error" => "Datos de usuario inválidos."];
        }

        $sql_update = "UPDATE users SET rol_id = ? WHERE id = ?";
        $stmt = $conexion->prepare($sql_update);

        if ($stmt) {
            // Si el rol_id es 0, lo ponemos como NULL en la base de datos
            if ($rol_id == 0) {
                $rol_id = null;
            }
            $stmt->bind_param("ii", $rol_id, $user_id);

            if ($stmt->execute()) {
                $stmt->close();
                return ["exito" => true];
            } else {
                $error = $stmt->error;
                $stmt->close();
                return ["exito" => false, "error" => "Error al actualizar el rol: $error"];
            }
        } else {
            return ["exito" => false, "error" => "Error al preparar la consulta: " . $conexion->error];
        }
    }
}
