<?php
include_once(__DIR__ . "/../../lib/common.php");
include_once(__DIR__ . "/../../lib/constants.php");
include_once(__DIR__ . "/../../lib/helpers.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: " . VIEWS_URL . "auth/login.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p class='alert alert-danger'>ID de comentario inválido.</p>";
    exit();
}

$comentario_id = $_GET['id'];

if (verificarPermiso(1)) {
    // Si usuario es administrador, puede eliminar cualquier comentario
    $sql_eliminar = "DELETE FROM comentarios WHERE id = ?";
    $stmt_eliminar = $conexion->prepare($sql_eliminar);

    if ($stmt_eliminar) {
        $stmt_eliminar->bind_param("i", $comentario_id);
        if ($stmt_eliminar->execute()) {
            
            if (isset($_GET['id_articulo'])) {
                header("Location: " . VIEWS_URL . "articles/articles.php?id=" . urlencode($_GET['id_articulo']) . "&mensaje=comentario_eliminado");
            } else {
                header("Location: " . VIEWS_URL . "managers/gestor_comentarios.php?mensaje=comentario_eliminado");
            }
            exit();
        } else {
            echo "<p class='alert alert-danger'>Error al eliminar el comentario.</p>";
        }
        $stmt_eliminar->close();
    } else {
        echo "<p class='alert alert-danger'>Error al preparar la consulta de eliminación.</p>";
    }
} else {
    // El usuario no es administrador, verificar si es el autor del comentario
    $sql_verificar_autor = "SELECT usuario_id FROM comentarios WHERE id = ?";
    $stmt_verificar_autor = $conexion->prepare($sql_verificar_autor);

    if ($stmt_verificar_autor) {
        $stmt_verificar_autor->bind_param("i", $comentario_id);
        $stmt_verificar_autor->execute();
        $result_verificar_autor = $stmt_verificar_autor->get_result();

        if ($result_verificar_autor->num_rows == 1) {
            $row_autor = $result_verificar_autor->fetch_assoc();
            $usuario_id_comentario = $row_autor['usuario_id'];

            // Verificar si el usuario actual es el autor del comentario
            if ($_SESSION['user_id'] == $usuario_id_comentario) {
                
                $sql_eliminar_autor = "DELETE FROM comentarios WHERE id = ?";
                $stmt_eliminar_autor = $conexion->prepare($sql_eliminar_autor);

                if ($stmt_eliminar_autor) {
                    $stmt_eliminar_autor->bind_param("i", $comentario_id);
                    if ($stmt_eliminar_autor->execute()) {
                        
                        if (isset($_GET['id_articulo'])) {
                            header("Location: " . VIEWS_URL . "articles/articles.php?id=" . urlencode($_GET['id_articulo']) . "&mensaje=comentario_eliminado");
                        } else {
                            header("Location: " . VIEWS_URL . "admin/gestor_comentarios.php?mensaje=comentario_eliminado");
                        }
                        exit();
                    } else {
                        echo "<p class='alert alert-danger'>Error al eliminar el comentario.</p>";
                    }
                    $stmt_eliminar_autor->close();
                } else {
                    echo "<p class='alert alert-danger'>Error al preparar la consulta de eliminación.</p>";
                }
            } else {
                echo "<p class='alert alert-danger'>No tienes permiso para eliminar este comentario.</p>";
            }
        } else {
            echo "<p class='alert alert-danger'>Comentario no encontrado.</p>";
        }
        $stmt_verificar_autor->close();
    } else {
        echo "<p class='alert alert-danger'>Error al preparar la consulta para verificar el autor.</p>";
    }
}
?>