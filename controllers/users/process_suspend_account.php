<?php
include_once(__DIR__ . "/../../lib/constants.php");
include_once(__DIR__ . "/../../lib/common.php");
include_once(__DIR__ . "/../../lib/user.php");
include_once(__DIR__ . "/../../lib/helpers.php");
include_once(__DIR__ . "/../../lib/session.php");

if (!verificarPermiso(1)) {
    header("Location:" . BASE_URL . "index.php?error=permiso_denegado");
    exit();
}

/**
 * Limpia los datos de entrada para prevenir ataques XSS y SQL Injection.
 *
 * @param string $data El dato a limpiar.
 * @return string El dato limpio.
 */
function limpiarDatos($data) {
    global $conexion;
    return mysqli_real_escape_string($conexion, strip_tags(trim($data)));
}

/**
 * Suspende la cuenta de un usuario en la base de datos.
 *
 * @param mysqli $conexion La conexión a la base de datos.
 * @param int $user_id El ID del usuario.
 * @param string $razon_suspension La razón de la suspensión.
 * @return bool True si la suspensión fue exitosa, false en caso contrario.
 */
function suspenderCuenta($conexion, $user_id, $razon_suspension) {
    $sql = "UPDATE users SET state = ?, suspension_reason = ? WHERE id = ?";
    $stmt = mysqli_prepare($conexion, $sql);

    if ($stmt) {
        $estado = 'suspendido';
        mysqli_stmt_bind_param($stmt, "ssi", $estado, $razon_suspension, $user_id);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return true;
        } else {
            error_log("Error al ejecutar la consulta suspenderCuenta: " . mysqli_error($conexion));
            mysqli_stmt_close($stmt);
            return false;
        }
    } else {
        error_log("Error al preparar la consulta suspenderCuenta: " . mysqli_error($conexion));
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = isset($_POST['user_id']) ? limpiarDatos($_POST['user_id']) : null;
    $razon_suspension = isset($_POST['razon_suspension']) ? limpiarDatos($_POST['razon_suspension']) : null;

    if (empty($user_id) || empty($razon_suspension)) {
        $_SESSION['mensaje'] = "Todos los campos son obligatorios.";
        header("Location: " . BASE_URL . "views/managers/gestor_usuarios.php");
        exit();
    }

    // Suspender la cuenta del usuario
    $resultado = suspenderCuenta($conexion, $user_id, $razon_suspension);

    if ($resultado) {
        $_SESSION['mensaje'] = "Cuenta suspendida con éxito.";
    } else {
        $_SESSION['mensaje'] = "Error al suspender la cuenta.";
    }

    header("Location: " . BASE_URL . "views/managers/gestor_usuarios.php");
    exit();
} else {
    header("Location: " . BASE_URL . "index.php");
    exit();
}
?>
