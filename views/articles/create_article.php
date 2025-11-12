<?php
/**
 * Página para la creación de un nuevo artículo.
 *
 * Permite al usuario ingresar el título, el contenido, seleccionar una imagen
 * de portada, añadir etiquetas y elegir una categoría para el artículo.
 * Los datos del formulario se envían a 'process_articles.php' para su procesamiento.
 */

include_once(__DIR__ . "/../../lib/constants.php");
include_once(__DIR__ . "/../../lib/common.php");
include_once(__DIR__ . "/../../lib/categories.php");
include_once(__DIR__ . "/../../lib/helpers.php");

if (!(verificarPermiso(1) || verificarPermiso(2))) {
    header("Location:" . BASE_URL . "index.php?error=permiso_denegado");
    exit();
}

$categorias = obtenerNombreCategorias($conexion);
?>

<?php
$tituloPagina = "Crear Artículo";
include(__DIR__ . "/../../includes/head.php");
?>

<?php include(__DIR__ . "/../../includes/head.php"); ?>
<script src="<?php echo ASSETS_URL; ?>tinymce/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#editor',
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | alignleft aligncenter alignright alignjustify lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
            default_font_size: '18pt',
            font_formats: 'Arial=arial,helvetica,sans-serif;' +
                            'Times New Roman=times new roman,times,serif;' +
                            'Courier New=courier new,courier,monospace;',
            content_style:'body { font-family: "Arial", sans-serif; font-size: 18pt; }',
            setup: function (editor) {
                editor.on('init', function () {
                    editor.execCommand('JustifyLeft');
                    <?php if (isset($_SESSION['form_data']['article'])): ?>
                        editor.setContent(<?php echo json_encode($_SESSION['form_data']['article']); ?>);
                    <?php endif; ?>
                });
            }
        });
    </script>

<body class="d-flex flex-column min-vh-100">

    <?php
    // Display success message
    if (isset($_SESSION['success_message'])) {
        echo '<div class="alert alert-success text-center mt-3">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
        unset($_SESSION['success_message']);
    }

    // Display validation errors
    if (isset($_SESSION['form_errors']) && !empty($_SESSION['form_errors'])) {
        echo '<div class="alert alert-danger mt-3"><ul>';
        foreach ($_SESSION['form_errors'] as $error) {
            echo '<li>' . htmlspecialchars($error) . '</li>';
        }
        echo '</ul></div>';
        unset($_SESSION['form_errors']); // Clear errors after displaying
    }
    ?>

    <div id="loading-overlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Cargando...</span>
        </div>
    </div>

    <?php include(__DIR__ . "/../../includes/header.php"); ?>
    <div class="mt-5 p-5 mb-5 flex-grow-1">
        <div class="row">
            <div class="col-12 col-md-9">
                <form action="<?php echo CONTROLLERS_URL; ?>articles/process_articles.php" method="post" enctype="multipart/form-data">
                    <div class="mb-4">
                        <input type="text" name="title" id="title" class="form-control my-2" placeholder="Título" required value="<?php echo htmlspecialchars($_SESSION['form_data']['title'] ?? ''); ?>">
                        <textarea rows="25" name="article" id="editor" placeholder="Empieza a escribir tu nuevo artículo..."><?php echo htmlspecialchars($_SESSION['form_data']['article'] ?? ''); ?></textarea>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="">
                        <button class="btn btn-primary mt-2" type="submit">Publicar</button><br>
                        <div class="form-control my-3 w-100 p-3">
                            <label class="form-label fw-semibold fs-6 text-primary w-100" for="portada_articulo">Seleccione una imagen de portada para su artículo.</label>
                            <input type="file" id="portada_articulo" name="image" accept="image/*" />
                            <small class="form-text text-muted">Formatos permitidos: JPG, JPEG, PNG.</small>
                        </div>
                        <!--<div class="form-control my-3 w-100 p-3">
                            <label class="form-label fw-semibold fs-6 text-primary" for="etiquetas">Añade etiquetas a tu artículo para que sea más fácil encontrarlo.</label>
                            <textarea class="w-100" id="etiquetas" name="tags" placeholder="Separa las etiquetas con comas"></textarea>
                        </div>-->
                        <div class="form-control my-3 w-100 p-3">
                            <label class="form-label fw-semibold fs-6 text-primary" for="select_category">Seleccione la Categoría.</label>
                            <select class="form-select" name="category" id="select_category" required>
                                <option value="">Seleccione una categoría.</option>
                                <?php
                                $selectedCategory = $_SESSION['form_data']['category'] ?? '';
                                foreach ($categorias as $categoria):
                                    $categoryId = htmlspecialchars($categoria['id']); // Assuming 'id' is the value to match
                                    $categoryName = htmlspecialchars($categoria['nombre']);
                                    $selected = ($categoryId == $selectedCategory) ? 'selected' : '';
                                ?>
                                    <option value="<?= $categoryId ?>" <?= $selected ?>><?= $categoryName ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
<?php unset($_SESSION['form_data']); // Clear form data after displaying ?>
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
