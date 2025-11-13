<?php
include_once(__DIR__ . "/constants.php");
include_once(__DIR__ . "/common.php");

function obtenerCategorias($conexion) {
    // Seleccionamos todas las columnas necesarias para mostrar las categorías, incluyendo imagen y texto.
    $sql_categorias = "SELECT id, nombre, imagen, text FROM category ORDER BY nombre ASC";
    $result_categorias = mysqli_query($conexion, $sql_categorias);

    if ($result_categorias && mysqli_num_rows($result_categorias) > 0) {
        $categorias = [];
        while ($row_categoria = mysqli_fetch_assoc($result_categorias)) {
            $categorias[] = $row_categoria;
        }
        mysqli_free_result($result_categorias);
        return $categorias;
    }

    return [];
}

function ListadoCategorias($conexion) {
    $sql = "SELECT nombre FROM category ORDER BY nombre ASC";
    $result = $conexion->query($sql);

    if ($result) {
        $categorias = [];
        while ($row = $result->fetch_assoc()) {
            $categorias[] = $row['nombre'];
        }
        return $categorias;
    } else {
        return false; // Si hay un error en la consulta
    }
}

/*
Obtiene el nombre de las categorías desde la base de datos.
*/
function obtenerNombreCategorias($conexion) {
    $sql = "SELECT nombre FROM category";
    $result = mysqli_query($conexion, $sql);

    $categorias = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $categorias[] = $row;
        }
        mysqli_free_result($result);
    } else {
        error_log("Error al obtener categorías: " . mysqli_error($conexion));
    }

    return $categorias;
}

function obtenerArticulosPorCategoria($conexion, $idCategoria) {
    $sql = "SELECT
                a.*,
                u.username
            FROM
                articles a
                JOIN
                users u ON a.user = u.id
            WHERE
                a.category = ?";

    $stmt = $conexion->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $idCategoria);
        $stmt->execute();
        $result = $stmt->get_result();

        $articulos = [];
        while ($row = $result->fetch_assoc()) {
            $articulos[] = $row;
        }

        $stmt->close();
        return $articulos;
    } else {
        error_log("Error al preparar la consulta: " . $conexion->error, 0);
        return false;
    }
}

function paginarCategorias($categorias, $itemsPorPagina, $paginaActual) {
    $totalCategorias = count($categorias);
    $totalPaginas = ceil($totalCategorias / $itemsPorPagina);

    // Validar que la página actual no exceda los límites
    if ($paginaActual < 1) {
        $paginaActual = 1;
    } elseif ($paginaActual > $totalPaginas) {
        $paginaActual = $totalPaginas;
    }

    // Calcular el índice de inicio y fin
    $inicio = ($paginaActual - 1) * $itemsPorPagina;
    $categoriasPaginadas = array_slice($categorias, $inicio, $itemsPorPagina);

    return [
        'categorias' => $categoriasPaginadas,
        'totalPaginas' => $totalPaginas,
        'paginaActual' => $paginaActual
    ];
}
