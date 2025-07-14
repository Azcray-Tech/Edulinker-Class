<?php

/**
 * Obtiene los artículos con paginación y búsqueda.
 *
 * @param mysqli $conexion Conexión a la base de datos.
 * @param string $buscar Término de búsqueda.
 * @param int $paginaActual Página actual.
 * @param int $articulosPorPagina Número de artículos por página.
 * @return array Lista de artículos.
 */
function obtenerArticulos($conexion, $buscar, $paginaActual, $articulosPorPagina) {
    $offset = ($paginaActual - 1) * $articulosPorPagina;
    $whereClause = '';

    if (!empty($buscar)) {
        $buscar = $conexion->real_escape_string($buscar);
        $whereClause = "WHERE title LIKE '%$buscar%' OR category LIKE '%$buscar%'";
    }

    $sql = "SELECT id, user, title, image, category, date FROM articles $whereClause ORDER BY date DESC, id DESC LIMIT $articulosPorPagina OFFSET $offset";
    $result = $conexion->query($sql);

    $articulos = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $articulos[] = $row;
        }
    }

    return $articulos;
}

/**
 * Cuenta el número total de artículos con un filtro de búsqueda.
 *
 * @param mysqli $conexion Conexión a la base de datos.
 * @param string $buscar Término de búsqueda.
 * @return int Número total de artículos.
 */
function contarArticulos($conexion, $buscar) {
    $whereClause = '';

    if (!empty($buscar)) {
        $buscar = $conexion->real_escape_string($buscar);
        $whereClause = "WHERE title LIKE '%$buscar%' OR category LIKE '%$buscar%'";
    }

    $sql = "SELECT COUNT(*) AS total FROM articles $whereClause";
    $result = $conexion->query($sql);

    if ($result) {
        return $result->fetch_assoc()['total'];
    }

    return 0;
}