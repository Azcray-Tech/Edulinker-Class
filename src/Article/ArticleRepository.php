<?php
namespace App\Article;

use App\Core\Database;

class ArticleRepository {
    /**
     * Devuelve un mysqli_result con los artículos paginados.
     * Mantiene compatibilidad con el código legacy que espera un resultado mysqli.
     * @param int $limit
     * @param int $offset
     * @return \mysqli_result|false
     */
    public function getArticles(int $limit, int $offset, string $buscar = '')
    {
        $conexion = Database::getConnection();

        if (!empty($buscar)) {
            $like = '%' . $conexion->real_escape_string($buscar) . '%';
            $sql = "SELECT a.*, u.username
                    FROM articles a
                    JOIN users u ON a.user = u.id
                    WHERE a.title LIKE ? OR a.article LIKE ? OR a.category = ?
                    ORDER BY a.date DESC, a.id DESC
                    LIMIT ? OFFSET ?";

            $stmt = $conexion->prepare($sql);
            if (!$stmt) {
                error_log('ArticleRepository prepare error (search): ' . $conexion->error);
                return false;
            }
            $stmt->bind_param('sssii', $like, $like, $buscar, $limit, $offset);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result;
        }

        $sql = "SELECT a.*, u.username
                FROM articles a
                JOIN users u ON a.user = u.id
                ORDER BY a.date DESC, a.id DESC
                LIMIT ? OFFSET ?";

        $stmt = $conexion->prepare($sql);
        if (!$stmt) {
            error_log('ArticleRepository prepare error: ' . $conexion->error);
            return false;
        }
        $stmt->bind_param('ii', $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

    /**
     * Cuenta artículos (útil para paginación).
     * @return int
     */
    public function countArticles(string $buscar = ''): int
    {
        $conexion = Database::getConnection();

        if (!empty($buscar)) {
            // Usar prepared statements para evitar SQL injection
            $like = '%' . $buscar . '%';
            $sql = "SELECT COUNT(*) as total FROM articles WHERE title LIKE ? OR category = ?";
            $stmt = $conexion->prepare($sql);
            if (!$stmt) {
                error_log('ArticleRepository count prepare error: ' . $conexion->error);
                return 0;
            }
            $stmt->bind_param('ss', $like, $like);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res) {
                $row = $res->fetch_assoc();
                $res->free();
                $stmt->close();
                return (int)$row['total'];
            }
            $stmt->close();
            return 0;
        }

        $sql = "SELECT COUNT(*) as total FROM articles";
        $res = $conexion->query($sql);
        if ($res) {
            $row = $res->fetch_assoc();
            $res->free();
            return (int)$row['total'];
        }
        error_log('ArticleRepository count error: ' . $conexion->error);
        return 0;
    }

    /**
     * Obtener un artículo por su ID.
     * @param int $id
     * @return array|null
     */
    public function getArticleById(int $id): ?array
    {
        $conexion = Database::getConnection();
        $sql = "SELECT a.*, u.username
                FROM articles a
                JOIN users u ON a.user = u.id
                WHERE a.id = ? LIMIT 1";
        $stmt = $conexion->prepare($sql);
        if (!$stmt) {
            error_log('ArticleRepository getArticleById prepare error: ' . $conexion->error);
            return null;
        }
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $row = $res->fetch_assoc()) {
            $res->free();
            $stmt->close();
            return $row;
        }
        if ($stmt) {
            $stmt->close();
        }
        return null;
    }

    /**
     * Obtener artículos por categoría. Devuelve un mysqli_result si se solicita resultado raw,
     * o false en caso de error.
     * @param string $category
     * @param int|null $limit
     * @param int $offset
     * @return \mysqli_result|false
     */
    public function getArticlesByCategory(string $category, ?int $limit = null, int $offset = 0)
    {
        $conexion = Database::getConnection();

        if ($limit !== null) {
            $sql = "SELECT a.*, u.username
                    FROM articles a
                    JOIN users u ON a.user = u.id
                    WHERE a.category = ?
                    ORDER BY a.date DESC, a.id DESC
                    LIMIT ? OFFSET ?";
            $stmt = $conexion->prepare($sql);
            if (!$stmt) {
                error_log('ArticleRepository getArticlesByCategory prepare error: ' . $conexion->error);
                return false;
            }
            $stmt->bind_param('sii', $category, $limit, $offset);
            $stmt->execute();
            return $stmt->get_result();
        }

        $sql = "SELECT a.*, u.username
                FROM articles a
                JOIN users u ON a.user = u.id
                WHERE a.category = ?
                ORDER BY a.date DESC, a.id DESC";
        $stmt = $conexion->prepare($sql);
        if (!$stmt) {
            error_log('ArticleRepository getArticlesByCategory prepare error: ' . $conexion->error);
            return false;
        }
        $stmt->bind_param('s', $category);
        $stmt->execute();
        return $stmt->get_result();
    }

    /**
     * Contar artículos por usuario (útil para paginación en perfiles)
     * @param int $userId
     * @return int
     */
    public function countArticlesByUser(int $userId): int
    {
        $conexion = Database::getConnection();
        $sql = "SELECT COUNT(*) as total FROM articles WHERE user = ?";
        $stmt = $conexion->prepare($sql);
        if (!$stmt) {
            error_log('ArticleRepository countArticlesByUser prepare error: ' . $conexion->error);
            return 0;
        }
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $row = $res->fetch_assoc()) {
            $total = (int)$row['total'];
            $res->free();
            $stmt->close();
            return $total;
        }
        $stmt->close();
        return 0;
    }

    /**
     * Obtener artículos por usuario. Devuelve mysqli_result o false en caso de error.
     * @param int $userId
     * @param int $limit
     * @param int $offset
     * @return \mysqli_result|false
     */
    public function getArticlesByUser(int $userId, int $limit, int $offset)
    {
        $conexion = Database::getConnection();
        $sql = "SELECT id, title, article, image FROM articles WHERE user = ? ORDER BY date DESC, id DESC LIMIT ? OFFSET ?";
        $stmt = $conexion->prepare($sql);
        if (!$stmt) {
            error_log('ArticleRepository getArticlesByUser prepare error: ' . $conexion->error);
            return false;
        }
        $stmt->bind_param('iii', $userId, $limit, $offset);
        $stmt->execute();
        return $stmt->get_result();
    }

    /**
     * Contar artículos por categoría.
     * @param string $category
     * @return int
     */
    public function countArticlesByCategory(string $category): int
    {
        $conexion = Database::getConnection();
        $sql = "SELECT COUNT(*) as total FROM articles WHERE category = ?";
        $stmt = $conexion->prepare($sql);
        if (!$stmt) {
            error_log('ArticleRepository countArticlesByCategory prepare error: ' . $conexion->error);
            return 0;
        }
        $stmt->bind_param('s', $category);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $row = $res->fetch_assoc()) {
            $total = (int)$row['total'];
            $res->free();
            $stmt->close();
            return $total;
        }
        $stmt->close();
        return 0;
    }

    /**
     * Insertar un artículo.
     * @param string $title
     * @param string $article
     * @param string $image
     * @param string $category
     * @param int $userId
     * @return bool
     */
    public function insertArticle(string $title, string $article, string $image, string $category, int $userId): bool
    {
        $conexion = Database::getConnection();
        $sql = "INSERT INTO articles (title, article, image, category, user, date) VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $conexion->prepare($sql);
        if (!$stmt) {
            error_log('ArticleRepository insertArticle prepare error: ' . $conexion->error);
            return false;
        }
    $stmt->bind_param('ssssi', $title, $article, $image, $category, $userId);
        // Note: category is string but bind_param 'i' used for userId; ensure types
        $ok = $stmt->execute();
        $stmt->close();
        return (bool)$ok;
    }

    /**
     * Update an article. If $image is null, image won't be updated.
     * @param int $id
     * @param string $title
     * @param string $article
     * @param string|null $image
     * @param string $category
     * @return bool
     */
    public function updateArticle(int $id, string $title, string $article, ?string $image, string $category): bool
    {
        $conexion = Database::getConnection();
        if ($image !== null) {
            $sql = "UPDATE articles SET title = ?, article = ?, category = ?, image = ? WHERE id = ?";
            $stmt = $conexion->prepare($sql);
            if (!$stmt) {
                error_log('ArticleRepository updateArticle prepare error: ' . $conexion->error);
                return false;
            }
            $stmt->bind_param('ssssi', $title, $article, $category, $image, $id);
        } else {
            $sql = "UPDATE articles SET title = ?, article = ?, category = ? WHERE id = ?";
            $stmt = $conexion->prepare($sql);
            if (!$stmt) {
                error_log('ArticleRepository updateArticle prepare error: ' . $conexion->error);
                return false;
            }
            $stmt->bind_param('sssi', $title, $article, $category, $id);
        }

        $ok = $stmt->execute();
        $stmt->close();
        return (bool)$ok;
    }

    /**
     * Delete an article by id and optional user check.
     * @param int $id
     * @param int|null $userId If provided, delete only if user matches.
     * @return bool
     */
    public function deleteArticle(int $id, ?int $userId = null): bool
    {
        $conexion = Database::getConnection();
        if ($userId !== null) {
            $sql = "DELETE FROM articles WHERE id = ? AND user = ?";
            $stmt = $conexion->prepare($sql);
            if (!$stmt) {
                error_log('ArticleRepository deleteArticle prepare error: ' . $conexion->error);
                return false;
            }
            $stmt->bind_param('ii', $id, $userId);
        } else {
            $sql = "DELETE FROM articles WHERE id = ?";
            $stmt = $conexion->prepare($sql);
            if (!$stmt) {
                error_log('ArticleRepository deleteArticle prepare error: ' . $conexion->error);
                return false;
            }
            $stmt->bind_param('i', $id);
        }

        $ok = $stmt->execute();
        $stmt->close();
        return (bool)$ok;
    }
}
