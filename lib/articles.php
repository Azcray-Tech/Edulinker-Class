<?php
/**
 * Funciones para manejar artículos en la base de datos.
 * @package Blog
 */

/**
 * Inserta un nuevo artículo en la base de datos.
 * @param mysqli $conexion Conexión a la base de datos.
 * @param string $titulo Título del artículo.
 * @param string $contenido Contenido del artículo.
 * @param string $imagen archivo de la imagen del artículo.
 * @param string $categoria Categoría del artículo.
 * @param int $usuario ID del usuario que creó el artículo.
 * @return bool True si se insertó correctamente, false en caso contrario.
 * @throws Exception Si ocurre un error al preparar o ejecutar la consulta.
 */
function insertarArticulo($conexion, $titulo, $contenido, $imagen, $categoria, $usuario) {
    // Intentar usar ArticleService/Repository si existe (mantener compatibilidad)
    try {
        if (!class_exists(\App\Article\ArticleService::class)) {
            if (file_exists(__DIR__ . "/../vendor/autoload.php")) {
                require_once __DIR__ . "/../vendor/autoload.php";
            }
        }

        if (class_exists(\App\Article\ArticleService::class)) {
            $service = new \App\Article\ArticleService();
            return $service->createArticle($titulo, $contenido, $imagen, $categoria, (int)$usuario);
        }
    } catch (\Throwable $e) {
        error_log('ArticleService insertarArticulo error: ' . $e->getMessage());
    }

    $sql = "INSERT INTO articles (title, article, image, category, user, date) VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ssssis", $titulo, $contenido, $imagen, $categoria, $usuario);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}


/**
 * obtiene un artículo por su ID y el ID del usuario.
 * @param mysqli $conexion Conexión a la base de datos.
 * @param int $article_id ID del artículo.
 * @param int $user_id ID del usuario.
 * @return array|null Datos del artículo o null si no se encuentra.
 */
function obtenerArticuloPorId($conexion, $article_id, $user_id) {
    // Intentar usar ArticleRepository/Service
    try {
        if (!class_exists(\App\Article\ArticleRepository::class)) {
            if (file_exists(__DIR__ . "/../vendor/autoload.php")) {
                require_once __DIR__ . "/../vendor/autoload.php";
            }
        }

        if (class_exists(\App\Article\ArticleRepository::class)) {
            $repo = new \App\Article\ArticleRepository();
            $art = $repo->getArticleById((int)$article_id);
            if ($art && isset($art['user']) && (int)$art['user'] === (int)$user_id) {
                return ['title' => $art['title']];
            }
            return null;
        }
    } catch (\Throwable $e) {
        error_log('ArticleRepository obtenerArticuloPorId error: ' . $e->getMessage());
    }

    $sql = "SELECT title FROM articles WHERE id = ? AND user = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $article_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $article_data = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $article_data;
    }

    mysqli_stmt_close($stmt);
    return null;
}

/**
 * Obtiene todos los artículos de la base de datos.
 * @param mysqli $conexion Conexión a la base de datos.
 * @param int $limite Número máximo de artículos a obtener.
 * @param int $offset Desplazamiento para la paginación.
 * @return mysqli_result Resultado de la consulta.
 */
function obtenerArticulos($conexion, $limite, $offset) {
    // Preferir ArticleRepository (devuelve mysqli_result) para compatibilidad
    try {
        if (!class_exists(\App\Article\ArticleRepository::class)) {
            if (file_exists(__DIR__ . "/../vendor/autoload.php")) {
                require_once __DIR__ . "/../vendor/autoload.php";
            }
        }

        if (class_exists(\App\Article\ArticleRepository::class)) {
            $repo = new \App\Article\ArticleRepository();
            return $repo->getArticles((int)$limite, (int)$offset);
        }
    } catch (\Throwable $e) {
        error_log('ArticleRepository obtenerArticulos error: ' . $e->getMessage());
    }

    $sql = "SELECT a.id, a.title, a.date, a.image, a.article, u.username, a.category
            FROM articles a
            JOIN users u ON a.user = u.id
            ORDER BY a.date DESC, a.id DESC
            LIMIT ? OFFSET ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ii", $limite, $offset);
    $stmt->execute();
    return $stmt->get_result();
}

/**
 * Cuenta el número total de artículos en la base de datos.
 * @param mysqli $conexion Conexión a la base de datos.
 * @return int Número total de artículos.
 */
function contarArticulos($conexion) {
    try {
        if (!class_exists(\App\Article\ArticleService::class)) {
            if (file_exists(__DIR__ . "/../vendor/autoload.php")) {
                require_once __DIR__ . "/../vendor/autoload.php";
            }
        }

        if (class_exists(\App\Article\ArticleService::class)) {
            $service = new \App\Article\ArticleService();
            return $service->countArticles();
        }
    } catch (\Throwable $e) {
        error_log('ArticleService contarArticulos error: ' . $e->getMessage());
    }

    $sql = "SELECT COUNT(*) AS total FROM articles";
    $result = $conexion->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'];
}

/**
 * Elimina un artículo de la base de datos.
 * @param mysqli $conexion Conexión a la base de datos.
 * @param int $article_id ID del artículo a eliminar.
 * @param int $user_id ID del usuario que creó el artículo.
 * @return bool True si se eliminó correctamente, false en caso contrario.
 * @throws Exception Si ocurre un error al preparar o ejecutar la consulta.
 */
function eliminarArticulo($conexion, $article_id, $user_id) {
    try {
        if (!class_exists(\App\Article\ArticleService::class)) {
            if (file_exists(__DIR__ . "/../vendor/autoload.php")) {
                require_once __DIR__ . "/../vendor/autoload.php";
            }
        }

        if (class_exists(\App\Article\ArticleService::class)) {
            $service = new \App\Article\ArticleService();
            return $service->deleteArticle((int)$article_id, (int)$user_id);
        }
    } catch (\Throwable $e) {
        error_log('ArticleService eliminarArticulo error: ' . $e->getMessage());
    }

    $sql = "DELETE FROM articles WHERE id = ? AND user = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $article_id, $user_id);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}

/**
 * Busca artículos en la base de datos por título o contenido.
 * @param mysqli $conexion Conexión a la base de datos.
 * @param string $search Término de búsqueda.
 * @return array Lista de artículos que coinciden con la búsqueda.
 * @throws Exception Si ocurre un error al preparar o ejecutar la consulta.
 */
function buscarArticulos($conexion, $search) {
    // Intentar usar ArticleService
    try {
        if (!class_exists(\App\Article\ArticleService::class)) {
            if (file_exists(__DIR__ . "/../vendor/autoload.php")) {
                require_once __DIR__ . "/../vendor/autoload.php";
            }
        }

        if (class_exists(\App\Article\ArticleService::class)) {
            $service = new \App\Article\ArticleService();
            return $service->getArticlesArray(1000, 0, $search);
        }
    } catch (\Throwable $e) {
        error_log('ArticleService buscarArticulos error: ' . $e->getMessage());
    }

    $search = $conexion->real_escape_string($search);
    $sql = "SELECT a.id, a.title, a.date, a.image, a.article, u.username, a.category
            FROM articles a
            JOIN users u ON a.user = u.id
            WHERE a.title LIKE '%$search%' OR a.article LIKE '%$search%'
            ORDER BY a.date DESC, a.id DESC";
    $result = $conexion->query($sql);

    $articulos = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $articulos[] = $row;
        }
    }

    return $articulos;
}
