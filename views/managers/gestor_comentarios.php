<?php
/**
 * Página para la gestión de comentarios por parte del administrador.
 *
 * Permite visualizar, buscar y eliminar comentarios realizados por los usuarios
 * en los diferentes artículos. Utiliza paginación para manejar grandes cantidades
 * de comentarios.
 */

include(__DIR__ . "/../../lib/constants.php");
include_once(__DIR__ . "/../../lib/common.php");

include(__DIR__ . "/../../lib/helpers.php");

if (!verificarPermiso(1)) {
    header("Location:" . BASE_URL . "index.php?error=permiso_denegado");
    exit();
}

// --- Configuración de Paginación para Comentarios ---
$comentariosPorPagina = 10; // Define el número de comentarios a mostrar por página.
$paginaActualComentarios = isset($_GET['pagina_comentarios']) ? intval($_GET['pagina_comentarios']) : 1;
$offsetComentarios = ($paginaActualComentarios - 1) * $comentariosPorPagina;

// --- Configuración de Búsqueda en Comentarios ---
$buscarComentario = isset($_GET['buscar_comentario']) ? $conexion->real_escape_string($_GET['buscar_comentario']) : '';
$whereClauseComentarios = '';

// Construye la cláusula WHERE para la búsqueda si se proporciona un término.
if (!empty($buscarComentario)) {
    $whereClauseComentarios = "WHERE c.contenido LIKE '%$buscarComentario%'
                                    OR u.username LIKE '%$buscarComentario%'
                                    OR a.title LIKE '%$buscarComentario%'";
}

// --- Consulta para Obtener los Comentarios de la Página Actual con Filtro y JOINs ---
$sqlComentarios = "SELECT c.id,
                                     a.title AS articulo_titulo,
                                     a.id AS articulo_id,
                                     u.username AS usuario,
                                     c.contenido,
                                     c.fecha_creacion
                                FROM comentarios c
                                INNER JOIN users u ON c.usuario_id = u.id
                                INNER JOIN articles a ON c.articulo_id = a.id
                                $whereClauseComentarios
                                ORDER BY c.fecha_creacion DESC
                                LIMIT $comentariosPorPagina OFFSET $offsetComentarios";
$resultComentarios = $conexion->query($sqlComentarios);

// --- Consulta para Obtener el Número Total de Comentarios con Filtro de Búsqueda ---
$sqlTotalComentarios = "SELECT COUNT(*) AS total
                                     FROM comentarios c
                                     INNER JOIN users u ON c.usuario_id = u.id
                                     INNER JOIN articles a ON c.articulo_id = a.id
                                     $whereClauseComentarios";
$resultTotalComentarios = $conexion->query($sqlTotalComentarios);
$totalComentarios = $resultTotalComentarios->fetch_assoc()['total'];
$totalPaginasComentarios = ceil($totalComentarios / $comentariosPorPagina);

$mensaje_confirmacion = null;
$id_eliminar = null;
$usuario_eliminar = null;
$comentario_eliminar = null;

if (isset($_GET['confirmar']) && isset($_GET['id_eliminar'])) {
    $id_eliminar = $_GET['id_eliminar'];
    $sql_comentario_info = "SELECT u.username, c.contenido FROM comentarios c
                             INNER JOIN users u ON c.usuario_id = u.id
                             WHERE c.id = " . $conexion->real_escape_string($id_eliminar);
    $result_comentario_info = $conexion->query($sql_comentario_info);
    $info_comentario = $result_comentario_info->fetch_assoc();

    if ($info_comentario) {
        $usuario_eliminar = htmlspecialchars($info_comentario['username']);
        $comentario_eliminar = htmlspecialchars($info_comentario['contenido']);
        $mensaje_confirmacion = "¿Seguro que quieres eliminar el siguiente comentario:
                                  \"<strong>" . $usuario_eliminar . "</strong>:
                                  " . $comentario_eliminar . "\"?
                                  <a href='" . CONTROLLERS_URL . "comments/delete_comments.php?id=" . htmlspecialchars($id_eliminar) . "' class='btn btn-danger btn-sm ms-2'>Eliminar</a>
                                  <a href='gestor_comentarios.php' class='btn btn-secondary btn-sm ms-2'>Cancelar</a>";
    } else {
        $mensaje_confirmacion = "No se pudo obtener la información del comentario.";
    }
}
?>

<?php
$tituloPagina = "Gestor de Comentarios";
include(__DIR__ . "/../../includes/head.php");
?>

<body class="d-flex flex-column min-vh-100">

    <div id="loading-overlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Cargando...</span>
        </div>
    </div>

    <?php include(__DIR__ . "/../../includes/header.php"); ?>
    <?php include(__DIR__ . "/../../includes/asideAdmin.php"); ?>

    <div class="col-lg-8">
        <div class="row mb-5">
            <h1 class="fw-bolder mb-4">Gestión de Comentarios</h1>

            <form method="GET" action="gestor_comentarios.php" class="mb-3">
                <div class="input-group">
                    <input type="text" name="buscar_comentario" class="form-control" placeholder="Buscar por contenido, usuario o artículo" value="<?php echo htmlspecialchars($buscarComentario); ?>">
                    <button class="btn btn-outline-secondary" type="submit">Buscar</button>
                </div>
            </form>

            <?php if ($mensaje_confirmacion): ?>
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                    <div>
                        <?php echo $mensaje_confirmacion; ?>
                    </div>
                </div>
            <?php endif; ?>

            <table class="table">
                <thead>
                    <tr>
                        <th>Artículo</th>
                        <th>Usuario</th>
                        <th>Comentario</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Verifica si hay comentarios para mostrar.
                    if ($resultComentarios && $resultComentarios->num_rows > 0) {
                        // Itera sobre cada comentario obtenido de la base de datos.
                        while ($rowComentario = $resultComentarios->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td style='vertical-align: middle;'><a href='" . VIEWS_URL . "articles/articles.php?id=" . htmlspecialchars($rowComentario["articulo_id"]) . "' target='_blank'>" . htmlspecialchars($rowComentario["articulo_titulo"]) . "</a></td>";
                            echo "<td style='vertical-align: middle;'>" . htmlspecialchars($rowComentario["usuario"]) . "</td>";
                            // Muestra una porción del comentario con un "..." si es muy largo.
                            echo "<td style='vertical-align: middle;'>" . substr(htmlspecialchars($rowComentario["contenido"]), 0, 100) . (strlen($rowComentario["contenido"]) > 100 ? '...' : '') . "</td>";
                            echo "<td style='vertical-align: middle;'>" . htmlspecialchars($rowComentario["fecha_creacion"]) . "</td>";
                            echo "<td style='vertical-align: middle;'>
                                    <div class='dropdown'>
                                        <button class='btn btn-danger btn-sm dropdown-toggle' type='button' id='dropdownMenuButtonComentario_" . htmlspecialchars($rowComentario["id"]) . "' data-bs-toggle='dropdown' aria-expanded='false'>
                                            Acciones
                                        </button>
                                        <ul class='dropdown-menu' aria-labelledby='dropdownMenuButtonComentario_" . htmlspecialchars($rowComentario["id"]) . "'>
                                            <li><a class='dropdown-item' href='?confirmar=true&id_eliminar=" . htmlspecialchars($rowComentario["id"]) . "'>Eliminar</a></li>
                                        </ul>
                                    </div>
                                </td>";
                            echo "</tr>";
                        }
                    } else {
                        // Muestra un mensaje si no se encontraron comentarios.
                        echo "<tr><td colspan='5'>No se encontraron comentarios</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <nav aria-label="Paginación de Comentarios">
                <ul class="pagination justify-content-center">
                    <?php
                    // Enlace a la página anterior si no estamos en la primera página.
                    if ($paginaActualComentarios > 1): ?>
                        <li class="page-item"><a class="page-link" href="?pagina_comentarios=<?php echo $paginaActualComentarios - 1; if (!empty($buscarComentario)) { echo '&buscar_comentario=' . htmlspecialchars($buscarComentario); } ?>">Anterior</a></li>
                    <?php endif; ?>

                    <?php
                    // Genera los enlaces a cada página.
                    for ($i = 1; $i <= $totalPaginasComentarios; $i++): ?>
                        <li class="page-item <?php echo ($i == $paginaActualComentarios) ? 'active' : ''; ?>"><a class="page-link" href="?pagina_comentarios=<?php echo $i; if (!empty($buscarComentario)) { echo '&buscar_comentario=' . htmlspecialchars($buscarComentario); } ?>"><?php echo $i; ?></a></li>
                    <?php endfor; ?>

                    <?php
                    // Enlace a la página siguiente si no estamos en la última página.
                    if ($paginaActualComentarios < $totalPaginasComentarios): ?>
                        <li class="page-item"><a class="page-link" href="?pagina_comentarios=<?php echo $paginaActualComentarios + 1; if (!empty($buscarComentario)) { echo '&buscar_comentario=' . htmlspecialchars($buscarComentario); } ?>">Siguiente</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
    </div>
    </div>
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8.982 1.562a.7.7 0 0 1 .692 0l9.56 5.84c.528.32.528.823 0 1.143L8.982 14.938a.7.7 0 0 1-.692 0l-9.56-5.84c-.528-.32-.528-.823 0-1.143L8.982 1.562zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
        </symbol>
    </svg>
    <?php
    // Incluye el pie de página.
    include(__DIR__ . "/../../includes/footer.php");
    ?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const loadingOverlay = document.getElementById("loading-overlay");

            // Mostrar el overlay al cargar la página
            loadingOverlay.classList.add("show");

            // Ocultar el overlay después de que la página haya cargado completamente
            window.addEventListener("load", function () {
                loadingOverlay.classList.remove("show");
            });
        });
    </script>
    <script src="<?php echo __DIR__ . '/../../js/scripts.js'; ?>"></script>
</body>
</html>