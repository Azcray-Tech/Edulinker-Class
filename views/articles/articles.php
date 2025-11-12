<?php
/**
 * Página para mostrar un artículo individual detallado.
 *
 * Utiliza el ID del artículo proporcionado en la URL para obtener la información
 * del artículo de la base de datos y mostrarla. Incluye funcionalidades para
 * mostrar el encabezado, el contenido del artículo, comentarios y una barra
 * lateral relacionada.
 */

include(__DIR__ . "/../../lib/constants.php");
include(__DIR__ . "/../../lib/common.php");
include(__DIR__ . "/../../lib/articles.php");
include(__DIR__ . "/../../lib/notifications.php");

// Asegurar que $user_id existe y proviene de la sesión si el usuario está logueado.
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if (isset($_GET['notification_id']) && is_numeric($_GET['notification_id']) && isset($_SESSION['user_id'])) {
    $notification_id = $_GET['notification_id'];
    $user_id_logueado = $_SESSION['user_id'];

    if (marcarNotificacionComoLeida($conexion, $notification_id, $user_id_logueado)) {
        header("Location: " . VIEWS_URL . "articles/articles.php?id=" . urlencode($_GET['id']) . "#comentarios");
        exit(); // Asegúrate de salir después de la redirección
    } else {
        error_log("Error: No se pudo marcar la notificación con ID $notification_id como leída para el usuario con ID $user_id_logueado", 0);
        header("Location: " . VIEWS_URL . "articles/articles.php?id=" . urlencode($_GET['id']) . "#comentarios&error_marcar_leida"); // Redirigir incluso si hay error (opcionalmente puedes añadir un parámetro de error)
        exit(); // Asegúrate de salir después de la redirección
    }
}

$seguir_leyendo = SEGUIR_LEYENDO;
$id_post = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;

if (!$id_post) {
    echo "<p class='container mt-5'>Artículo no encontrado.</p>";
    exit;
}

// Intentar obtener el artículo usando el nuevo servicio (compatibilidad gradual)
$articulo = null;
try {
    if (!class_exists('\App\Article\ArticleService')) {
        if (file_exists(__DIR__ . "/../../vendor/autoload.php")) {
            require_once __DIR__ . "/../../vendor/autoload.php";
        }
    }
    $articleService = new \App\Article\ArticleService();
    $articulo = $articleService->getArticleById($id_post);
} catch (\Throwable $e) {
    error_log('ArticleService getArticleById error: ' . $e->getMessage());
    // Fallback al método legacy
    $articulo = obtenerArticulo($conexion, $id_post);
}

if ($articulo) {
    //mostrarHeader(htmlspecialchars($articulo['title']));
    ?>

<?php
$tituloPagina = $articulo ? ($articulo['title']) : "Artículo no encontrado";
include(__DIR__ . "/../../includes/head.php"); ?>

<body class="d-flex flex-column min-vh-100">

    <div id="loading-overlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Cargando...</span>
        </div>
    </div>

    <?php include(__DIR__ . "/../../includes/header.php"); ?>
    <div class="container flex-grow-1">
        <div class="row">
            <div class="col-lg-8">
                <article>
                    <header class="mb-4">
                        <h1 class="fw-bolder mb-1 mt-5"><?php echo htmlspecialchars($articulo['title']) ?></h1>

                    </header>

<figure class="mb-4"><img class="img-fluid rounded" width="1000px" src="<?php echo UPLOADS_URL . "articles/cover/" . htmlspecialchars($articulo['image']); ?>" alt="<?php echo htmlspecialchars($articulo['title']); ?>" /></figure>
                    <div class="text-muted fst-italic mb-2">Publicado el <?php echo date("d/m/Y", strtotime($articulo['date'])) ?> por <?php echo htmlspecialchars($articulo['username']) ?></div>
                    <a class="badge bg-primary text-decoration-none link-light mb-2" href="<?php echo VIEWS_URL; ?>articles/articles_category.php?id=<?php echo htmlspecialchars($articulo['category']); ?>"><?php echo htmlspecialchars($articulo['category']); ?></a>
                    <section class="mb-5">
                        <p style="text-align: justify;" class="fs-5 mb-4" contenteditable="false"><?php echo sanitizeArticleContent($articulo['article']) ?></p>
                    </section>
                </article>
                <?php
                include_once(__DIR__ . "/../../includes/comments.php");
                ?>
            </div>
            <?php
            include_once(__DIR__ . "/../../includes/aside.php");
            ?>
        </div>
    </div>

    <?php
} else {
    error_log("Error: No se pudo obtener el artículo con ID $id_post", 0);
    //mostrarHeader("Artículo no encontrado");
    echo "<p class='container mt-5'>Artículo no encontrado.</p>";
    exit;
}
?>

<?php
include(__DIR__ . "/../../includes/footer.php");
?>
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
