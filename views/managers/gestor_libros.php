<?php
include(__DIR__ . "/../../lib/constants.php");
include_once(__DIR__ . "/../../lib/common.php");
include(__DIR__ . "/../../lib/helpers.php");

if (!verificarPermiso(1)) {
    header("Location:" . BASE_URL . "index.php?error=permiso_denegado");
    exit();
}

// Variables de paginación para libros
$librosPorPagina = 5; // Puedes ajustar este número
$paginaActualLibros = isset($_GET['pagina_libros']) ? $_GET['pagina_libros'] : 1;
$offsetLibros = ($paginaActualLibros - 1) * $librosPorPagina;

// Variables para la búsqueda
$buscarLibro = isset($_GET['buscar_libro']) ? $conexion->real_escape_string($_GET['buscar_libro']) : '';
$whereClauseLibros = '';

if (!empty($buscarLibro)) {
    $whereClauseLibros = "WHERE title LIKE '%$buscarLibro%' OR category LIKE '%$buscarLibro%' OR author LIKE '%$buscarLibro%'";
}

// Consulta para obtener los libros de la página actual con filtro de búsqueda
$sqlLibros = "SELECT id, title, image, category, summary, date, author, book FROM books $whereClauseLibros ORDER BY id DESC LIMIT $librosPorPagina OFFSET $offsetLibros";
$resultLibros = $conexion->query($sqlLibros);

// Consulta para obtener el número total de libros con filtro de búsqueda
$sqlTotalLibros = "SELECT COUNT(*) AS total FROM books $whereClauseLibros";
$resultTotalLibros = $conexion->query($sqlTotalLibros);
$totalLibros = $resultTotalLibros->fetch_assoc()['total'];
$totalPaginasLibros = ceil($totalLibros / $librosPorPagina);
?>

<?php
$tituloPagina = "Gestor de Libros";
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
            <h1 class="fw-bolder mb-4">Gestión de Libros</h1>

            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#subirLibroModal">
                Subir Nuevo Libro
            </button>

            <div class="modal fade" id="subirLibroModal" tabindex="-1" aria-labelledby="subirLibroModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="subirLibroModalLabel">Subir Nuevo Libro</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="<?php echo CONTROLLERS_URL . 'books/procces_book.php'; ?>" enctype="multipart/form-data" id="formSubirLibro">
                                <input type="hidden" name="libro_id" id="libro_id_editar"> <div class="mb-3">
                                    <label for="titulo" class="form-label">Título</label>
                                    <input type="text" class="form-control" id="titulo" name="titulo" required>
                                </div>
                                <div class="mb-3">
                                    <label for="autor" class="form-label">Autor</label>
                                    <input type="text" class="form-control" id="autor" name="autor" required>
                                </div>
                                <div class="mb-3">
                                    <label for="categoria" class="form-label">Categoría</label>
                                    <input type="text" class="form-control" id="categoria" name="categoria">
                                </div>
                                <div class="mb-3">
                                    <label for="portada" class="form-label">Subir Portada</label>
                                    <input type="file" class="form-control" id="portada" name="portada" accept="image/*">
                                    <div id="portada-actual" class="form-text"></div> </div>
                                <div class="mb-3">
                                    <label for="libro_archivo" class="form-label">Subir Libro (PDF, etc.)</label>
                                    <input type="file" class="form-control" id="libro_archivo" name="libro_archivo" accept=".pdf,.epub,.mobi">
                                    <div id="libro-actual" class="form-text"></div> </div>
                                <div class="mb-3">
                                    <label for="resumen" class="form-label">Resumen</label>
                                    <textarea class="form-control" id="resumen" name="resumen" rows="3"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary float-end mt-2" id="btnSubirLibro">Subir Libro</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="eliminarLibroModal" tabindex="-1" aria-labelledby="eliminarLibroModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="eliminarLibroModalLabel">Confirmar Eliminación</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <p>¿Estás seguro de que deseas eliminar este libro?</p>
                            <div id="libro-a-eliminar"></div> </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <a href="#" id="boton-eliminar-definitivo" class="btn btn-danger">Eliminar</a>
                        </div>
                    </div>
                </div>
            </div>

            <form method="GET" action="gestor_libros.php" class="mb-3">
                <div class="input-group">
                    <input type="text" name="buscar_libro" class="form-control" placeholder="Buscar por título, categoría o autor" value="<?php echo htmlspecialchars($buscarLibro); ?>">
                    <button class="btn btn-outline-secondary" type="submit">Buscar</button>
                </div>
            </form>

            <table class="table">
                <thead>
                    <tr>
                        <th>Portada</th>
                        <th>Título</th>
                        <th>Categoría</th>
                        <th>Autor</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($resultLibros->num_rows > 0) {
                        while ($rowLibro = $resultLibros->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>";
                            if (!empty($rowLibro["image"])) {
                                echo '<div style="width: 48px; height: 64px; overflow: hidden;">';
                                echo '<img src="' . UPLOADS_URL . 'books/cover/' . $rowLibro["image"] . '" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;" alt="' . htmlspecialchars($rowLibro["title"]) . '">';
                                echo '</div>';
                            } else {
                                echo '<div style="width: 40px; height: 60px; background-color: #f0f0f0; display: flex; justify-content: center; align-items: center; font-size: 0.6em; color: #777;">Sin portada</div>';
                            }
                            echo "</td>";
                            echo "<td style='vertical-align: middle;'>" . htmlspecialchars($rowLibro["title"]) . "</td>";
                            echo "<td style='vertical-align: middle;'>" . htmlspecialchars($rowLibro["category"]) . "</td>";
                            echo "<td style='vertical-align: middle;'>" . htmlspecialchars($rowLibro["author"]) . "</td>";
                            echo "<td style='vertical-align: middle;'>" . htmlspecialchars($rowLibro["date"]) . "</td>";
                            echo "<td style='vertical-align: middle;'>
                                        <div class='dropdown'>
                                            <button class='btn btn-warning btn-sm dropdown-toggle' type='button' id='dropdownMenuButtonLibro_" . $rowLibro["id"] . "' data-bs-toggle='dropdown' aria-expanded='false'>
                                                Acciones
                                            </button>
                                            <ul class='dropdown-menu' aria-labelledby='dropdownMenuButtonLibro_" . $rowLibro["id"] . "'>
                                                <li><a class='dropdown-item btn-editar-libro' href='#' data-bs-toggle='modal' data-bs-target='#subirLibroModal' data-id='" . $rowLibro["id"] . "' data-titulo='" . htmlspecialchars($rowLibro["title"]) . "' data-autor='" . htmlspecialchars($rowLibro["author"]) . "' data-categoria='" . htmlspecialchars($rowLibro["category"]) . "' data-resumen='" . htmlspecialchars($rowLibro["summary"]) . "' data-portada='" . htmlspecialchars($rowLibro["image"]) . "' data-libro='" . htmlspecialchars($rowLibro["book"]) . "'>Editar</a></li>
                                                <li><a class='dropdown-item btn-eliminar-libro' href='#' data-bs-toggle='modal' data-bs-target='#eliminarLibroModal' data-id='" . $rowLibro["id"] . "' data-titulo='" . htmlspecialchars($rowLibro["title"]) . "'>Eliminar</a></li>
                                            </ul>
                                        </div>
                                    </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No se encontraron libros</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <nav aria-label="Paginación de Libros">
                <ul class="pagination justify-content-center">
                    <?php if ($paginaActualLibros > 1): ?>
                        <li class="page-item"><a class="page-link" href="?pagina_libros=<?php echo $paginaActualLibros - 1; if (!empty($buscarLibro)) { echo '&buscar_libro=' . htmlspecialchars($buscarLibro); } ?>">Anterior</a></li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPaginasLibros; $i++): ?>
                        <li class="page-item <?php echo ($i == $paginaActualLibros) ? 'active' : ''; ?>"><a class="page-link" href="?pagina_libros=<?php echo $i; if (!empty($buscarLibro)) { echo '&buscar_libro=' . htmlspecialchars($buscarLibro); } ?>"><?php echo $i; ?></a></li>
                    <?php endfor; ?>

                    <?php if ($paginaActualLibros < $totalPaginasLibros): ?>
                        <li class="page-item"><a class="page-link" href="?pagina_libros=<?php echo $paginaActualLibros + 1; if (!empty($buscarLibro)) { echo '&buscar_libro=' . htmlspecialchars($buscarLibro); } ?>">Siguiente</a></li>
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
            const subirLibroModal = document.getElementById('subirLibroModal');
            const modalSubirLibroLabel = document.getElementById('subirLibroModalLabel');
            const btnSubirLibro = document.getElementById('btnSubirLibro');
            const formSubirLibro = document.getElementById('formSubirLibro');
            const libroIdEditar = document.getElementById('libro_id_editar');
            const tituloInput = document.getElementById('titulo');
            const autorInput = document.getElementById('autor');
            const categoriaInput = document.getElementById('categoria');
            const resumenInput = document.getElementById('resumen');
            const portadaActualDiv = document.getElementById('portada-actual');
            const libroActualDiv = document.getElementById('libro-actual');

            const eliminarLibroModal = document.getElementById('eliminarLibroModal');
            const libroAEliminarDiv = document.getElementById('libro-a-eliminar');
            const botonEliminarDefinitivo = document.getElementById('boton-eliminar-definitivo');
            let libroIdAEliminar;

            // Mostrar el overlay al cargar la página
            loadingOverlay.classList.add("show");

            // Ocultar el overlay después de que la página haya cargado completamente
            window.addEventListener("load", function () {
                loadingOverlay.classList.remove("show");
            });

            // Evento para preparar el modal de edición
            subirLibroModal.addEventListener('show.bs.modal', function (event) {
                const botonEditar = event.relatedTarget;
                const id = botonEditar.getAttribute('data-id');
                const titulo = botonEditar.getAttribute('data-titulo');
                const autor = botonEditar.getAttribute('data-autor');
                const categoria = botonEditar.getAttribute('data-categoria');
                const resumen = botonEditar.getAttribute('data-resumen');
                const portada = botonEditar.getAttribute('data-portada');
                const libro = botonEditar.getAttribute('data-libro');

                modalSubirLibroLabel.textContent = id ? 'Editar Libro' : 'Subir Nuevo Libro';
                btnSubirLibro.textContent = id ? 'Guardar Cambios' : 'Subir Libro';
                libroIdEditar.value = id ? id : '';
                tituloInput.value = titulo ? titulo : '';
                autorInput.value = autor ? autor : '';
                categoriaInput.value = categoria ? categoria : '';
                resumenInput.value = resumen ? resumen : '';
                portadaActualDiv.textContent = portada ? 'Portada actual: ' + portada : '';
                libroActualDiv.textContent = libro ? 'Archivo actual: ' + libro : '';

                // Cambiar la acción del formulario para la edición
                formSubirLibro.action = id ? '<?php echo CONTROLLERS_URL . 'books/edit_book.php'; ?>' : '<?php echo CONTROLLERS_URL . 'books/procces_book.php'; ?>';
            });

            // Evento para preparar el modal de eliminación
            eliminarLibroModal.addEventListener('show.bs.modal', function (event) {
                const botonEliminar = event.relatedTarget;
                const id = botonEliminar.getAttribute('data-id');
                const titulo = botonEliminar.getAttribute('data-titulo');
                libroIdAEliminar = id;
                libroAEliminarDiv.textContent = 'Libro: ' + titulo;
                botonEliminarDefinitivo.href = '<?php echo CONTROLLERS_URL . 'books/delete_book.php?id='; ?>' + libroIdAEliminar;
            });
        });
    </script>
</body>
</html>