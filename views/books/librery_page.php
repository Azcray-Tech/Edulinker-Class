<?php
include(__DIR__ . "/../../lib/constants.php");
include(__DIR__ . "/../../lib/common.php");

$seguir_leyendo = SEGUIR_LEYENDO;

// Variables para los filtros
$filtroCategoria = isset($_GET['categoria']) ? $conexion->real_escape_string($_GET['categoria']) : '';

// Configuración de la paginación
$librosPorPagina = 24;
$paginaActual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$offset = ($paginaActual - 1) * $librosPorPagina;

// Comprobar que la tabla 'books' exista para evitar excepciones si el esquema no la incluye.
function tableExists($conexion, $tableName) {
    $tbl = $conexion->real_escape_string($tableName);
    $res = $conexion->query("SHOW TABLES LIKE '$tbl'");
    return ($res && $res->num_rows > 0);
}

// Construcción de la cláusula WHERE para la consulta de conteo
$whereClauseCount = "WHERE 1=1";
$paramsCount = [];
$typesCount = "";

if (!empty($filtroCategoria)) {
    $whereClauseCount .= " AND category = ?";
    $paramsCount[] = $filtroCategoria;
    $typesCount .= "s";
}

// Consulta para obtener el total de libros filtrados
// Si la tabla 'books' no existe, evitamos ejecutar consultas que producirían excepciones
if (!tableExists($conexion, 'books')) {
    // Variables por defecto para la vista: no hay libros ni categorías
    $totalLibros = 0;
    $totalPaginas = 0;
    $resultLibros = false;
    $resultCategorias = false;
} else {
    $sqlTotalLibros = "SELECT COUNT(id) AS total FROM books $whereClauseCount";
    $stmtTotal = $conexion->prepare($sqlTotalLibros);

    if ($stmtTotal) {
        if (!empty($paramsCount)) {
            $stmtTotal->bind_param($typesCount, ...$paramsCount);
        }
        $stmtTotal->execute();
        $resultTotal = $stmtTotal->get_result();
        $totalLibros = $resultTotal->fetch_assoc()['total'] ?? 0;
        $stmtTotal->close();
    } else {
        echo "Error al preparar la consulta de conteo: " . $conexion->error;
        $totalLibros = 0;
    }

    $totalPaginas = ceil($totalLibros / $librosPorPagina);

    // Construcción de la cláusula WHERE para la consulta de libros con paginación
    $whereClauseLibros = "WHERE 1=1";
    $paramsLibros = [];
    $typesLibros = "";

    if (!empty($filtroCategoria)) {
        $whereClauseLibros .= " AND category = ?";
        $paramsLibros[] = $filtroCategoria;
        $typesLibros .= "s";
    }

    // Consulta para obtener los libros filtrados con paginación
    $sqlLibros = "SELECT id, title, image, category FROM books $whereClauseLibros LIMIT ?, ?";
    $stmtLibros = $conexion->prepare($sqlLibros);

    if ($stmtLibros) {
        $paramsBind = [...$paramsLibros, $offset, $librosPorPagina];
        $typesBind = $typesLibros . "ii";
        if (!empty($paramsBind)) {
            $stmtLibros->bind_param($typesBind, ...$paramsBind);
        } else {
            $stmtLibros->bind_param("ii", $offset, $librosPorPagina);
        }
        $stmtLibros->execute();
        $resultLibros = $stmtLibros->get_result();

        if (!$resultLibros) {
            echo "Error al ejecutar la consulta de libros: " . $conexion->error;
            $resultLibros = false;
        }
        $stmtLibros->close();
    } else {
        echo "Error al preparar la consulta de libros: " . $conexion->error;
        $resultLibros = false;
    }

    // Consulta para obtener todas las categorías únicas para el filtro
    $sqlCategorias = "SELECT DISTINCT category FROM books ORDER BY category ASC";
    $resultCategorias = $conexion->query($sqlCategorias);
}


include(__DIR__ . "/../../includes/head.php");
?>

<?php
$tituloPagina = "Biblioteca de Libros";
include(__DIR__ . "/../../includes/head.php");
?>

<body class="d-flex flex-column min-vh-100">

    <div id="loading-overlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Cargando...</span>
        </div>
    </div>

    <?php include(__DIR__ . "/../../includes/header.php"); ?>
    <div class="container mt-5 flex-grow-1">
        <div class="row">
            <div class="col-lg-12">
                <div class="row mb-4">
                    <h4 class="mb-3">Biblioteca</h4>
                    <hr class="mb-4">

                    <!--<div class="col-md-6 mb-4">
                        <label for="filtroCategoria" class="form-label">Filtrar por Categoría:</label>
                        <select class="form-select" id="filtroCategoria" name="categoria" onchange="this.form.submit()">
                            <option value="">Todas las Categorías</option>
                            <?php
                            if ($resultCategorias && $resultCategorias->num_rows > 0) {
                                while ($rowCategoria = $resultCategorias->fetch_assoc()) {
                                    $selected = ($filtroCategoria == $rowCategoria['category']) ? 'selected' : '';
                                    echo "<option value='" . htmlspecialchars($rowCategoria['category']) . "' " . $selected . ">" . htmlspecialchars($rowCategoria['category']) . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>-->
                    <form method="GET" action="" id="filtrosForm"></form>
                </div>

                <div class="row mb-5 justify-content-center">
                    <?php
                    if ($resultLibros && $resultLibros->num_rows > 0) {
                        while ($rowLibro = $resultLibros->fetch_assoc()) {
                            ?>
                            <div class="col-md-3 mb-4 d-flex justify-content-center">
                                <div class="card h-100 d-flex flex-column" style="width: 18rem;">
                                    <?php if (!empty($rowLibro["image"])): ?>
                                        <img src="<?php echo UPLOADS_URL . "books/cover/" . ($rowLibro["image"]); ?>" class="card-img-top img-fluid" style="height: 320px; object-fit: cover;" alt="<?php echo htmlspecialchars($rowLibro["title"]); ?>">
                                    <?php else: ?>
                                        <div style="height: 320px; background-color: #f0f0f0; display: flex; justify-content: center; align-items: center; font-size: 1.2em; color: #777;">Sin portada</div>
                                    <?php endif; ?>
                                    <div class="card-body text-center d-flex flex-column">
                                        <h5 class="card-title"><?php echo htmlspecialchars($rowLibro["title"]); ?></h5>
                                        <p class="card-text small">Categoría: <?php echo htmlspecialchars($rowLibro["category"]); ?></p>
                                        <div class="d-grid gap-2 mt-auto">
                                            <a href="<?php echo VIEWS_URL; ?>books/view_books.php?id=<?php echo $rowLibro["id"]; ?>" class="btn btn-primary">Ver</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<div class='col-12'><p>No se encontraron libros con los filtros seleccionados.</p></div>";
                    }
                    ?>
                </div>

                <?php if ($totalPaginas > 1): ?>
                    <nav aria-label="Paginación de Libros">
                        <ul class="pagination justify-content-center">
                            <?php if ($paginaActual > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?pagina=<?php echo $paginaActual - 1; ?><?php echo !empty($filtroCategoria) ? '&categoria=' . htmlspecialchars($filtroCategoria) : ''; ?>" aria-label="Anterior">
                                        Anterior
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php
                            // Calcular el rango de páginas a mostrar
                            $rango = 2; // Mostrar 2 páginas antes y después de la actual
                            $inicio = max(1, $paginaActual - $rango);
                            $fin = min($totalPaginas, $paginaActual + $rango);

                            // Mostrar la primera página si no está en el rango
                            if ($inicio > 1) {
                                echo '<li class="page-item"><a class="page-link" href="?pagina=1' . (!empty($filtroCategoria) ? '&categoria=' . htmlspecialchars($filtroCategoria) : '') . '">1</a></li>';
                                if ($inicio > 2) {
                                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                }
                            }

                            for ($i = $inicio; $i <= $fin; $i++) {
                                ?>
                                <li class="page-item <?php echo ($i == $paginaActual) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?pagina=<?php echo $i; ?><?php echo !empty($filtroCategoria) ? '&categoria=' . htmlspecialchars($filtroCategoria) : ''; ?>"><?php echo $i; ?></a>
                                </li>
                                <?php
                            }

                            // Mostrar la última página si no está en el rango
                            if ($fin < $totalPaginas) {
                                if ($fin < $totalPaginas - 1) {
                                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                }
                                echo '<li class="page-item"><a class="page-link" href="?pagina=' . $totalPaginas . (!empty($filtroCategoria) ? '&categoria=' . htmlspecialchars($filtroCategoria) : '') . '">' . $totalPaginas . '</a></li>';
                            }
                            ?>

                            <?php if ($paginaActual < $totalPaginas): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?pagina=<?php echo $paginaActual + 1; ?><?php echo !empty($filtroCategoria) ? '&categoria=' . htmlspecialchars($filtroCategoria) : ''; ?>" aria-label="Siguiente">
                                        Siguiente
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
            <?php // include("../../includes/aside.php"); ?>
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