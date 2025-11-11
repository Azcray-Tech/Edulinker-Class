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

    // Intentar usar ArticleService si está disponible (migración gradual)
    try {
        if (!class_exists(\App\Article\ArticleService::class)) {
            if (file_exists(__DIR__ . "/../vendor/autoload.php")) {
                require_once __DIR__ . "/../vendor/autoload.php";
            }
        }

        if (class_exists(\App\Article\ArticleService::class)) {
            $service = new \App\Article\ArticleService();
            return $service->getArticlesArray($articulosPorPagina, $offset, $buscar);
        }
    } catch (\Throwable $e) {
        error_log('ArticleService obtenerArticulos error: ' . $e->getMessage());
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

    // Intentar usar ArticleService si está disponible
    try {
        if (!class_exists(\App\Article\ArticleService::class)) {
            if (file_exists(__DIR__ . "/../vendor/autoload.php")) {
                require_once __DIR__ . "/../vendor/autoload.php";
            }
        }

        if (class_exists(\App\Article\ArticleService::class)) {
            $service = new \App\Article\ArticleService();
            return $service->countArticles($buscar);
        }
    } catch (\Throwable $e) {
        error_log('ArticleService contarArticulos error: ' . $e->getMessage());
    }

    $sql = "SELECT COUNT(*) AS total FROM articles $whereClause";
    $result = $conexion->query($sql);

    if ($result) {
        return $result->fetch_assoc()['total'];
    }

    return 0;
}