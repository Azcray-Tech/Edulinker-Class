<?php
include(__DIR__ . "/../../lib/constants.php");
include_once(__DIR__ . "/../../lib/common.php");
include(__DIR__ . "/../../lib/helpers.php");

if (!verificarPermiso(1)) {
    header("Location:" . BASE_URL . "index.php?error=permiso_denegado");
    exit();
}

// Variables de paginación
$categoriasPorPagina = 6;
$paginaActual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$offset = ($paginaActual - 1) * $categoriasPorPagina;

// Consulta para obtener las categorías de la página actual
$sql = "SELECT id, nombre, imagen, text FROM category LIMIT $categoriasPorPagina OFFSET $offset";
$result = $conexion->query($sql);

// Consulta para obtener el número total de categorías
$sqlTotal = "SELECT COUNT(*) AS total FROM category";
$resultTotal = $conexion->query($sqlTotal);
$totalCategorias = $resultTotal->fetch_assoc()['total'];
$totalPaginas = ceil($totalCategorias / $categoriasPorPagina);
?>

<?php
$tituloPagina = "Gestor de Categorías";
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
            <h1 class="fw-bolder mb-4">Gestión de Categorías</h1>

            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#agregarCategoriaModal">
                Agregar Categoría
            </button>

            <div class="modal fade" id="agregarCategoriaModal" tabindex="-1" aria-labelledby="agregarCategoriaModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="agregarCategoriaModalLabel">Agregar Categoría</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                                </div>
                                <div class="mb-3">
                                    <label for="imagen" class="form-label">Subir Imagen</label>
                                    <input type="file" class="form-control" id="imagen" name="imagen">
                                </div>
                                <div class="mb-3">
                                    <label for="text" class="form-label">Descripción</label>
                                    <textarea class="form-control" id="text" name="text"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary float-end mt-2">Agregar Categoría</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <h2 class="mt-4">Categorías Existentes</h2>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="col-md-4 mb-4">';
                    echo '<div class="card h-100 d-flex flex-column" style="width: 18rem;">';
                    echo '<img width="250" height="150" src="' . UPLOADS_URL . 'categories/cover/' . $row["imagen"] . '" class="card-img-top img-fluid" style="height: 150px; object-fit: cover;" alt="' . htmlspecialchars($row["nombre"]) . '">';
                    echo '<div class="card-body text-center d-flex flex-column">';
                    echo '<h5 class="card-title">' . htmlspecialchars($row["nombre"]) . '</h5>';
                    echo '<p class="card-text" style="text-align: center;">' . htmlspecialchars($row["text"]) . '</p>';
                    echo '<div class="d-grid gap-2 mt-auto">';
                    echo '<a href="' . __DIR__ . '/../../editar_categoria.php?id=' . $row["id"] . '" class="btn btn-sm btn-primary">Editar</a>';
                    echo '<a href="' . __DIR__ . '/../../eliminar_categoria.php?id=' . $row["id"] . '" class="btn btn-sm btn-danger">Eliminar</a>';
                    echo '</div>';
                    echo '</div></div></div>';
                }
            } else {
                echo '<div class="col-12"><p>No se encontraron categorías.</p></div>';
            }
            ?>

            <nav aria-label="Paginación">
                <ul class="pagination justify-content-center">
                    <?php if ($paginaActual > 1): ?>
                        <li class="page-item"><a class="page-link" href="?pagina=<?php echo $paginaActual - 1; ?>">Anterior</a></li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                        <li class="page-item <?php echo ($i == $paginaActual) ? 'active' : ''; ?>"><a class="page-link" href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                    <?php endfor; ?>

                    <?php if ($paginaActual < $totalPaginas): ?>
                        <li class="page-item"><a class="page-link" href="?pagina=<?php echo $paginaActual + 1; ?>">Siguiente</a></li>
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

<?php
// Procesamiento del formulario para agregar categorías
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $conexion->real_escape_string($_POST["nombre"]);
    $imagen = $_FILES["imagen"]["name"];
    $text = $conexion->real_escape_string($_POST["text"]);

    // Mover la imagen subida al directorio de uploads
    if (!empty($imagen)) {
        $rutaDestino = UPLOADS_URL . "categories/cover/" . basename($imagen);
        move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaDestino);
    }

    $sql = "INSERT INTO category (nombre, imagen, text) VALUES ('$nombre', '$imagen', '$text')";

    if ($conexion->query($sql) === TRUE) {
        echo "<script>alert('Categoría agregada con éxito'); window.location.href = 'gestor_categorias.php';</script>";
    } else {
        echo "<script>alert('Error al agregar categoría: " . $conexion->error . "');</script>";
    }
}
?>