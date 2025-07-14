<?php
include(__DIR__ . "/../../lib/constants.php");
include_once(__DIR__ . "/../../lib/common.php");
include_once(__DIR__ . "/../../lib/article_manager.php");
include(__DIR__ . "/../../lib/helpers.php");

if (!verificarPermiso(1)) {
    header("Location:" . BASE_URL . "index.php?error=permiso_denegado");
    exit();
}

// Variables de paginación y búsqueda
$articulosPorPagina = 7;
$paginaActualArticulos = isset($_GET['pagina_articulos']) ? (int)$_GET['pagina_articulos'] : 1;
$buscar = isset($_GET['buscar']) ? $_GET['buscar'] : '';

// Obtener los artículos y la información de paginación
$articulos = obtenerArticulos($conexion, $buscar, $paginaActualArticulos, $articulosPorPagina);
$totalArticulos = contarArticulos($conexion, $buscar);
$totalPaginasArticulos = ceil($totalArticulos / $articulosPorPagina);
?>

<?php
$tituloPagina = "Gestor de Artículos";
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
            <h1 class="fw-bolder mb-4">Gestión de Artículos</h1>
            <hr>

            <form method="GET" action="gestor_articulos.php" class="mb-3">
                <div class="input-group">
                    <input type="text" name="buscar" class="form-control" placeholder="Buscar por título o categoría" value="<?php echo htmlspecialchars($buscar); ?>">
                    <button class="btn btn-outline-secondary" type="submit">Buscar</button>
                </div>
            </form>

            <table class="table">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Título</th>
                        <th>Categoría</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($articulos)): ?>
                        <?php foreach ($articulos as $articulo): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($articulo["image"])): ?>
                                        <div style="width: 85px; height: 48px; overflow: hidden;">
                                            <img src="<?php echo UPLOADS_URL . 'articles/cover/' . htmlspecialchars($articulo["image"]); ?>" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;" alt="<?php echo htmlspecialchars($articulo["title"]); ?>">
                                        </div>
                                    <?php else: ?>
                                        <div style="width: 50px; height: 30px; background-color: #f0f0f0; display: flex; justify-content: center; align-items: center; font-size: 0.7em; color: #777;">Sin imagen</div>
                                    <?php endif; ?>
                                </td>
                                <td style="vertical-align: middle;"><?php echo htmlspecialchars($articulo["title"]); ?></td>
                                <td style="vertical-align: middle;"><?php echo htmlspecialchars($articulo["category"]); ?></td>
                                <td style="vertical-align: middle;"><?php echo htmlspecialchars($articulo["date"]); ?></td>
                                <td style="vertical-align: middle;">
                                    <div class="dropdown">
                                        <button class="btn btn-warning btn-sm dropdown-toggle" type="button" id="dropdownMenuButtonArticulo_<?php echo $articulo["id"]; ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                            Acciones
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButtonArticulo_<?php echo $articulo["id"]; ?>">
                                            <li><a class="dropdown-item" href="<?php echo VIEWS_URL . 'articles/edit_article.php?id=' . $articulo["id"]; ?>">Editar</a></li>
                                            <li><a class="dropdown-item" href="<?php echo VIEWS_URL . 'articles/delete_article.php?action=delete&id=' . $articulo["id"]; ?>">Eliminar</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5">No se encontraron artículos</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <nav aria-label="Paginación de Artículos">
                <ul class="pagination justify-content-center">
                    <?php if ($paginaActualArticulos > 1): ?>
                        <li class="page-item"><a class="page-link" href="?pagina_articulos=<?php echo $paginaActualArticulos - 1; if (!empty($buscar)) { echo '&buscar=' . htmlspecialchars($buscar); } ?>">Anterior</a></li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPaginasArticulos; $i++): ?>
                        <li class="page-item <?php echo ($i == $paginaActualArticulos) ? 'active' : ''; ?>"><a class="page-link" href="?pagina_articulos=<?php echo $i; if (!empty($buscar)) { echo '&buscar=' . htmlspecialchars($buscar); } ?>"><?php echo $i; ?></a></li>
                    <?php endfor; ?>

                    <?php if ($paginaActualArticulos < $totalPaginasArticulos): ?>
                        <li class="page-item"><a class="page-link" href="?pagina_articulos=<?php echo $paginaActualArticulos + 1; if (!empty($buscar)) { echo '&buscar=' . htmlspecialchars($buscar); } ?>">Siguiente</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
    </div>
    </div>
    <?php include(__DIR__ . "/../../includes/footer.php"); ?>

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
</body>
</html>
