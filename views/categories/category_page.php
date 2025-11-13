<?php
/*
Página para mostrar las categorías de artículos disponibles.
*/

include_once(__DIR__ . "/../../lib/constants.php");
include_once(__DIR__ . "/../../lib/common.php");
include_once(__DIR__ . "/../../lib/categories.php");

/*
Funcion que ya no voy a utilizar, eliminar mas tarde
mostrarHeader("Categorias de Articulos");
*/

$categorias = obtenerCategorias($conexion);

// Configuración de la paginación
$itemsPorPagina = 6;
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

// Obtener las categorías paginadas
$paginacion = paginarCategorias($categorias, $itemsPorPagina, $paginaActual);
$categoriasPaginadas = $paginacion['categorias'];
$totalPaginas = $paginacion['totalPaginas'];
?>

<?php
// Emcabezado de la página
$tituloPagina = "Categorías de Artículos";
include(__DIR__ . "/../../includes/head.php");
?>

<body class="d-flex flex-column min-vh-100">
    <!-- Capa de carga -->
    <div id="loading-overlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Cargando...</span>
        </div>
    </div>
<?php include(__DIR__ . "/../../includes/header.php"); ?>
    <div class="container mt-5 flex-grow-1">
        <div class="row">
            <div class="col-lg-12">
                <div class="row mb-3">
                    <h4 class="mb-3">Categorias</h4>
                    <hr class="mb-4">

                    <?php
                    // Verifica si hay categorías disponibles.
                    if (!empty($categoriasPaginadas)) {
                        foreach ($categoriasPaginadas as $categoria) {
                            echo '<div class="col-md-4 mb-4">';
                            echo '<div class="card h-100 d-flex flex-column" style="width: 25rem;" >';
                            // Si la categoría no tiene imagen o texto (esquema mínimo), usar placeholders.
                            $imagenCat = isset($categoria["imagen"]) && !empty($categoria["imagen"]) ? $categoria["imagen"] : 'placeholder-category.jpg';
                            $textoCat = isset($categoria["text"]) ? $categoria["text"] : '';
                            echo '<img width="250" height="150" src="' . UPLOADS_URL . 'categories/cover/' . $imagenCat . '" class="card-img-top img-fluid" style="height: 150px; object-fit: cover;" alt="' . $categoria["nombre"] . '">';
                            echo '<div class="card-body text-center d-flex flex-column">';
                            echo '<h5 class="card-title">' . $categoria["nombre"] . '</h5>';
                            if (!empty($textoCat)) {
                                echo '<p class="card-text text-center">' . $textoCat . '</p>';
                            }
                            echo '<div class="d-grid gap-2 mt-auto">';
                            echo '<a href="' . VIEWS_URL . 'articles/articles_category.php?id=' . $categoria['nombre'] . '" class="btn btn-primary">Ver Artículos</a>';
                            echo '</div>';
                            echo '</div></div></div>';
                        }
                    } else {
                        echo '<div class="col-12"><p>No se encontraron categorías.</p></div>';
                    }
                    ?>
                </div>

                <!-- Navegación de paginación -->
                <nav class="mb-5">
                    <ul class="pagination justify-content-center">
                        <?php if ($paginaActual > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?pagina=<?php echo $paginaActual - 1; ?>">Anterior</a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                            <li class="page-item <?php echo $i == $paginaActual ? 'active' : ''; ?>">
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
