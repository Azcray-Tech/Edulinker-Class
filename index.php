<?php
include_once(__DIR__ . "/lib/constants.php");
include_once(__DIR__ . "/lib/common.php");
include_once(__DIR__ . "/lib/articles.php");
include_once(__DIR__ . "/lib/helpers.php");

$seguirLeyendo = SEGUIR_LEYENDO;

// Configuración de la paginación
$articulosPorPagina = 5; // Número de artículos por página
$paginaActual = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($paginaActual - 1) * $articulosPorPagina;

// Obtener el total de artículos y calcular el número total de páginas
$totalArticulos = contarArticulos($conexion);
$totalPaginas = ceil($totalArticulos / $articulosPorPagina);

$result = obtenerArticulos($conexion, $articulosPorPagina, $offset);

$tituloPagina = "Artículos Recientes";
include_once(__DIR__ . "/includes/head.php");

// Buscar artículos si se proporciona una consulta de búsqueda
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $resultados = buscarArticulos($conexion, $search);
} else {
    $resultados = [];
}
?>

<body class="d-flex flex-column min-vh-100">
    <div id="loading-overlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Cargando...</span>
        </div>
    </div>
    <?php include_once(__DIR__ . "/includes/header.php"); ?>
    <div class="container flex-grow-1">
        <div class="row">
            <div class="col-lg-8 mt-5 mb-5">
                
                <?php if (isset($search)): ?>
                    <h4>Resultados de la búsqueda para "<?php echo htmlspecialchars($search); ?>"</h4>
                    <hr>
                    <?php if (count($resultados) > 0): ?>
                        <article>
                            <?php foreach ($resultados as $row): ?>
                                <?php mostrarArticulo($row, $seguirLeyendo); ?>
                            <?php endforeach; ?>
                        </article>
                    <?php else: ?>
                        <p>No se encontraron resultados para su búsqueda.</p>
                    <?php endif; ?>
                <?php else: ?>

                    <h4>Artículos Recientes</h4>
                    <hr>
                    <article>
                        <?php
                        if ($result) {
                            while ($row = $result->fetch_assoc()) {
                                mostrarArticulo($row, $seguirLeyendo);
                            }
                            $result->free();
                        } else {
                            error_log("Error en la consulta: " . $conexion->error, 0);
                        }
                        ?>
                    </article>

                    <!-- Paginación -->
                    <nav class="mb-5" aria-label="Paginación">
                        <ul class="pagination justify-content-center">
                            <?php if ($paginaActual > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?pagina=<?php echo $paginaActual - 1; ?>">Anterior</a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                                <li class="page-item <?php echo $i === $paginaActual ? 'active' : ''; ?>">
                                    <a class="page-link" href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($paginaActual < $totalPaginas): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?pagina=<?php echo $paginaActual + 1; ?>">Siguiente</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
            <?php include_once(__DIR__ . "/includes/aside.php"); ?>
        </div>
    </div>
    <?php include_once(__DIR__ . "/includes/footer.php"); ?>

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
