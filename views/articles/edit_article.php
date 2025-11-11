<?php
/**
 * Página para la edición de un artículo existente.
 *
 * Permite al usuario modificar el título, el contenido, la imagen de portada,
 * las etiquetas y la categoría de un artículo específico. Los datos del
 * formulario se envían a 'update_article.php' para su actualización.
 */

 include(__DIR__ . "/../../lib/constants.php");
 include_once(__DIR__ . "/../../lib/common.php");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location:" . __DIR__ . "/../../index.php");
    exit();
}

$article_id = $_GET['id'];

// Intentar obtener la información del artículo usando ArticleService (compatibilidad gradual)
$article_data = null;
try {
    if (!class_exists('\App\Article\ArticleService')) {
        if (file_exists(__DIR__ . "/../../vendor/autoload.php")) {
            require_once __DIR__ . "/../../vendor/autoload.php";
        }
    }
    if (class_exists('\App\Article\ArticleService')) {
        $service = new \App\Article\ArticleService();
        $article_data = $service->getArticleById($article_id);
    }
} catch (\Throwable $e) {
    error_log('ArticleService getArticleById error (edit): ' . $e->getMessage());
}

// Fallback legacy si no se obtuvo con el servicio
if (!$article_data) {
    $sql_articulo = "SELECT id, title, article, image, category FROM articles WHERE id = ?";
    $stmt_articulo = mysqli_prepare($conexion, $sql_articulo);
    mysqli_stmt_bind_param($stmt_articulo, "i", $article_id);
    mysqli_stmt_execute($stmt_articulo);
    $result_articulo = mysqli_stmt_get_result($stmt_articulo);

    if (!$result_articulo || mysqli_num_rows($result_articulo) == 0) {
        header("Location: index.php");
        exit();
    }

    $article_data = mysqli_fetch_assoc($result_articulo);
    mysqli_stmt_close($stmt_articulo);
}

$article_title = htmlspecialchars($article_data['title']);
$article_content = htmlspecialchars($article_data['article']);
$article_image = htmlspecialchars($article_data['image']);
$article_category_id = $article_data['category'];

// Obtener la lista de categorías para el select (usar helper legacy si está disponible)
$categories = [];
if (function_exists('obtenerCategorias')) {
    $cats = obtenerCategorias($conexion);
    foreach ($cats as $row_categoria) {
        $categories[$row_categoria['id']] = htmlspecialchars($row_categoria['nombre']);
    }
} else {
    // Fallback a consulta directa si no existe la función
    $sql_categorias = "SELECT id, nombre FROM category";
    $result_categorias = mysqli_query($conexion, $sql_categorias);
    if ($result_categorias) {
        while ($row_categoria = mysqli_fetch_assoc($result_categorias)) {
            $categories[$row_categoria['id']] = htmlspecialchars($row_categoria['nombre']);
        }
        mysqli_free_result($result_categorias);
    } else {
        error_log("Error en la consulta de nombres de categorías: " . mysqli_error($conexion), 0);
    }
}

?>

<?php
$tituloPagina = "Editar Artículo";
include(__DIR__ . "/../../includes/head.php");
?>
<script src="<?php echo ASSETS_URL; ?>tinymce/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    // Inicializa el editor TinyMCE al cargar la página.
    tinymce.init({
        selector: '#editor', // El ID del textarea donde se mostrará el editor.
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | alignleft aligncenter alignright alignjustify lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        default_font_size: '18pt', // Tamaño de letra por defecto. Con este mañano se ven bien los articulos
        font_formats: 'Arial=arial,helvetica,sans-serif;' +
                      'Times New Roman=times new roman,times,serif;' +
                      'Courier New=courier new,courier,monospace;',
        content_style: 'body { font-family: "Arial", sans-serif; font-size: 18pt; }', // Aplica la fuente y el tamaño al body del editor
        setup: function (editor) {
            editor.on('init', function () {
            editor.execCommand('AlignRight'); // Justificación por defecto
            });
        }
    });
</script>

<body class="d-flex flex-column min-vh-100">

    <div id="loading-overlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Cargando...</span>
        </div>
    </div>

    <?php include(__DIR__ . "/../../includes/header.php"); ?>

    <div class="mt-5 p-5 mb-5 flex-grow-1">
        <div class="row">
            <div class="col-12 col-md-9">
                <form action="<?php echo CONTROLLERS_URL; ?>articles/update_article.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="article_id" value="<?php echo $article_id; ?>">
                    <div class="mb-4">
                        <input type="text" name="title" id="title" class="form-control my-2" placeholder="Titulo" value="<?php echo $article_title; ?>" required>
                        <textarea rows="25" name="article" id="editor" placeholder="Empieza a escribir tu nuevo articulo..."><?php echo $article_content; ?></textarea>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="">
                        <button class="btn btn-primary mt-2" type="submit" name="update_article">Guardar Cambios</button><br>
                        <div class="form-control my-3 w-100 p-3">
                            <label class="form-label fw-semibold fs-6 text-primary w-100" for="portada_articulo">Seleccione una nueva imagen de portada para su artículo (opcional).</label>
                            <input type="file" id="portada_articulo" name="image" accept="image/*" />
                            <?php if ($article_image): ?>
                                <p class="mt-2">Imagen de portada actual: <?php echo ($article_image); ?></p>
                            <?php endif; ?>
                            <small class="form-text text-muted">Formatos permitidos: JPG, JPEG, PNG. Dejar en blanco para mantener la imagen actual.</small>
                            <input type="hidden" name="current_image" value="<?php echo $article_image; ?>">
                        </div>
                        <!--<div class="form-control my-3 w-100 p-3">
                            <label class="form-label fw-semibold fs-6 text-primary" for="etiquetas">Edita las etiquetas de tu articulo para que sea mas facil encontrarlo.</label>
                            <textarea class="w-100" id="etiquetas" name="tags" placeholder=" Separa las etiquetas con comas"><?php // echo $article_tags; ?></textarea>
                        </div>-->
                        <div class="form-control my-3 w-100 p-3">
                            <label class="form-label fw-semibold fs-6 text-primary" for="select_category">Seleccione la Categoria.</label>
                            <select class="form-select" name="category" id="select_category" required>
                                <option value="">Seleccione una categoría.</option>
                                <?php
                                if (!empty($categories)) {
                                    foreach ($categories as $cat_id => $cat_name) {
                                        echo "";
                                        echo "\n";
                                        echo "\n";
                                        $selected = ($cat_id == $article_category_id) ? 'selected' : '';
                                        echo "<option value=\"$cat_id\" $selected>$cat_name</option>";
                                    }
                                } else {
                                    echo "<option value=\"\">Error al cargar las categorías</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="mt-5">
        <?php include(__DIR__ . "/../../includes/footer.php"); ?>
    </div>
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
<?php
mysqli_close($conexion);
?>