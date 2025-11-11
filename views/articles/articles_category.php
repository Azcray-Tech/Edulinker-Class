<?php
/**
 * Página para mostrar los artículos pertenecientes a una categoría específica.
 */

include(__DIR__ . "/../../lib/constants.php");
include(__DIR__ . "/../../lib/common.php");
include(__DIR__ . "/../../lib/categories.php");

$seguir_leyendo = SEGUIR_LEYENDO;

// Obtener el ID de la categoría desde la URL
$id_category = $_GET['id'] ?? null;

// Validar que el ID de la categoría exista
if (!$id_category) {
    echo "<p class='container mt-5'>No ha especificado una categoría válida.</p>";
    exit;
}

// Obtener los artículos de la categoría (usar ArticleService cuando sea posible)
$articulos = [];
try {
    if (!class_exists('\App\Article\ArticleService')) {
        if (file_exists(__DIR__ . "/../../vendor/autoload.php")) {
            require_once __DIR__ . "/../../vendor/autoload.php";
        }
    }
    $articleService = new \App\Article\ArticleService();
    $articulos = $articleService->getArticlesByCategoryArray($id_category, null, 0);
} catch (\Throwable $e) {
    error_log('ArticleService getArticlesByCategoryArray error: ' . $e->getMessage());
    // Fallback al método legacy
    $articulos = obtenerArticulosPorCategoria($conexion, $id_category);
}

// Definir el título de la página de forma segura
$tituloPagina = "Artículos de ";
if ($articulos && count($articulos) > 0) {
    $tituloPagina .= ($articulos[0]['category']);
} else {
    $tituloPagina .= "Categoría Desconocida";
}

?>
<?php
include(__DIR__ . "/../../includes/head.php");
?>
<body class="d-flex flex-column min-vh-100">

    <div id="loading-overlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Cargando...</span>
        </div>
    </div>

<?php include(__DIR__ . "/../../includes/header.php"); ?>
<div class="container mb-5 flex-grow-1">
    <div class="row">
        <div class="col-lg-8 mt-5">
            <?php if ($articulos && count($articulos) > 0): ?>
                <header class="mb-4 text-lg-start">
                    <h4 class="mb-1">Categoría de Estudio: <?php echo ($articulos[0]['category']); ?></h4>
                    <hr>
                </header>

                <article>
                    <?php foreach ($articulos as $row): ?>
                        <h1 class="fw-bolder mb-3 mt-2"><?php echo ($row['title']); ?></h1>

                        <figure class="mb-4">
                            <img class="img-fluid rounded" width="1000px" src="<?php echo UPLOADS_URL . "articles/cover/". ($row['image']); ?>" alt="..." />
                        </figure>

                        <div class="text-muted fst-italic mb-2">
                            Publicado el <?php echo date("d/m/Y", strtotime($row['date'])); ?> por <?php echo ($row['username']); ?>
                        </div>

                        <a class="badge bg-primary text-decoration-none link-light mb-2" href="articles_category.php?id=<?php echo ($row['category']); ?>">
                            <?php echo ($row['category']); ?>
                        </a>

                        <section class="mb-5">
                            <p style="text-align: justify;" class="fs-5 mb-4"><?php echo cortarTexto(strip_tags($row['article'])); ?></p>

                            <a class="btn bg-primary text-decoration-none link-light mb-2" href="articles.php?id=<?php echo ($row['id']); ?>">
                                <?php echo ($seguir_leyendo); ?>
                            </a>
                        </section>
                    <?php endforeach; ?>
                </article>
            <?php else: ?>
                <h4 class="mb-1">Aún no se han publicado Artículos en esta Categoría.</h4>
                <hr>
                <p class="mt-2">
                    Sabemos que esta materia es de tu interés, por eso estamos trabajando en la redacción de sus artículos.
                    <br>
                    ¡Mantente atento, muy pronto habrá novedades!
                </p>
            <?php endif; ?>
        </div>
        <?php include(__DIR__ . "/../../includes/aside.php"); ?>
    </div>
</div>

<?php include(__DIR__ . "/../../includes/footer.php"); ?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const loadingOverlay = document.getElementById("loading-overlay");
            loadingOverlay.classList.add("show");
        });

        window.addEventListener("load", function () {
            const loadingOverlay = document.getElementById("loading-overlay");
            loadingOverlay.classList.remove("show");
        });
    </script>
</body>
</html>