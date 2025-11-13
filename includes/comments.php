<?php
include_once(__DIR__ . "/../lib/common.php"); // Ruta corregida para incluir common.php

function tiempo_transcurrido($datetime)
{
    $ahora = new DateTime();
    $pasado = new DateTime($datetime);
    $intervalo = $ahora->diff($pasado);

    $tiempo = '';

    if ($intervalo->y > 0) {
        $tiempo .= $intervalo->y . ' año' . ($intervalo->y > 1 ? 's' : '');
    } elseif ($intervalo->m > 0) {
        $tiempo .= $intervalo->m . ' mes' . ($intervalo->m > 1 ? 'es' : '');
    } elseif ($intervalo->d > 0) {
        $tiempo .= $intervalo->d . ' día' . ($intervalo->d > 1 ? 's' : '');
    } elseif ($intervalo->h > 0) {
        $tiempo .= $intervalo->h . ' hora' . ($intervalo->h > 1 ? 's' : '');
    } elseif ($intervalo->i > 0) {
        $tiempo .= $intervalo->i . ' minuto' . ($intervalo->i > 1 ? 's' : '');
    } else {
        $tiempo .= $intervalo->s . ' segundo' . ($intervalo->s != 1 ? 's' : '');
    }

    return 'hace ' . $tiempo;
}

// Obtener el ID del artículo de la URL
$articulo_id_actual = isset($_GET["id"]) && is_numeric($_GET["id"]) ? $_GET["id"] : null;

// Obtener el ID del comentario a editar si está presente
$comentario_id_editar = isset($_GET["editar"]) && is_numeric($_GET["editar"]) ? $_GET["editar"] : null;
$contenido_editar = '';

// Si se está editando un comentario, obtener su contenido
if ($comentario_id_editar && $articulo_id_actual) {
    $sql_editar = "SELECT contenido FROM comentarios WHERE id = ? AND articulo_id = ? AND usuario_id = ?";
    $stmt_editar = $conexion->prepare($sql_editar);
    if ($stmt_editar) {
        $stmt_editar->bind_param("iii", $comentario_id_editar, $articulo_id_actual, $_SESSION['user_id']);
        $stmt_editar->execute();
        $result_editar = $stmt_editar->get_result();
        if ($result_editar->num_rows == 1) {
            $row_editar = $result_editar->fetch_assoc();
            $contenido_editar = htmlspecialchars($row_editar["contenido"]);
        }
        $stmt_editar->close();
    }
}

// Consulta para obtener los comentarios del artículo actual
if ($articulo_id_actual) {
    $sql = "SELECT c.id, c.usuario_id, c.contenido, c.fecha_creacion, u.username
            FROM comentarios c
            INNER JOIN users u ON c.usuario_id = u.id
            WHERE c.articulo_id = ?
            ORDER BY c.fecha_creacion ASC";

    $stmt = $conexion->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $articulo_id_actual);
        $stmt->execute();
        $result = $stmt->get_result();

?>
        <section class="mb-3">
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="card-title mb-4"><?php echo $result->num_rows; ?> comentarios</h5>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="ms-3 mb-2">';
                            echo '<div class="fw-bold"><a href="' . VIEWS_URL . 'users/profile.php?id=' . urlencode($row["usuario_id"]) . '" class="text-decoration-none">' . htmlspecialchars($row["username"]) . '</a> <span class="text-muted small">' . tiempo_transcurrido($row["fecha_creacion"]) . '</span></div>';
                            echo htmlspecialchars($row["contenido"]);
                            echo '<div class="">';
                            if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row["usuario_id"]) {
                                echo '<a href="?id=' . urlencode($articulo_id_actual) . '&editar=' . urlencode($row["id"]) . '" class="text-decoration-none small">Editar</a> <span class="mx-1"></span>';
                                echo '<a href="' . CONTROLLERS_URL . 'comments/delete_comments.php?id=' . urlencode($row["id"]) . '&id_articulo=' . urlencode($articulo_id_actual) . '" class="text-decoration-none small">Eliminar</a>';
                            }
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo "Aún no hay comentarios para este artículo.";
                    }
                    ?>
                </div>
            </div>
        </section>
<?php

        $stmt->close();
    } else {
        echo "<p class='alert alert-danger'>Error al preparar la consulta de comentarios.</p>";
    }
} else {
    echo "<p class='alert alert-danger'>ID de artículo inválido.</p>";
}
?>

<section class="mb-5">
    <div class="card bg-light">
        <div class="card-body">
            <?php if (!isset($_SESSION['user_id'])): ?>
                <h5 class="fw-bolder mb-3">
                    ¡<a href="<?php echo VIEWS_URL; ?>auth/login.php" class="text-decoration-none">Inicia Sesión</a> para dejar un comentario!
                </h5>
            <?php else: ?>
                <h5 class="fw-bolder mb-3"><?php echo $comentario_id_editar ? 'Editar Comentario' : 'Dejar un Comentario'; ?></h5>
                <form method="POST" action="<?php echo CONTROLLERS_URL; ?>comments/<?php echo $comentario_id_editar ? 'edit_comments.php?id=' . urlencode($comentario_id_editar) . '&id_articulo=' . urlencode($articulo_id_actual) : 'procesar_comentario.php?id=' . urlencode($articulo_id_actual); ?>">
                    <textarea class="form-control" rows="3" name="contenido_comentario" placeholder="¡Únete a la conversación dejando un comentario!"><?php echo $contenido_editar; ?></textarea>
                    <?php if ($comentario_id_editar): ?>
                        <input type="hidden" name="id_comentario" value="<?php echo $comentario_id_editar; ?>">
                    <?php endif; ?>
                    <div class="mt-3">
                        <button class="btn btn-primary" type="submit"><?php echo $comentario_id_editar ? 'Guardar Edición' : 'Comentar'; ?></button>
                        <?php if ($comentario_id_editar): ?>
                            <a href="?id=<?php echo urlencode($articulo_id_actual); ?>" class="btn btn-secondary">Cancelar</a>
                        <?php endif; ?>
                    </div>
                </form>
            <?php endif; ?>
            <div class="d-flex">
            </div>
        </div>
    </div>
</section>