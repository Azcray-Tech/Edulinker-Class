<?php
namespace App\Article;

use App\Core\Cache;

class ArticleService {
    private ArticleRepository $repo;

    public function __construct(ArticleRepository $repo = null)
    {
        $this->repo = $repo ?? new ArticleRepository();
    }

    /**
     * Devuelve artículos como array asociativo (útil si queremos abstraer el uso de mysqli_result).
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getArticlesArray(int $limit, int $offset, string $buscar = ''): array
    {
        $cacheKey = 'articles_list_' . md5($limit . '_' . $offset . '_' . $buscar);
        $cachedArticles = Cache::get($cacheKey);

        if ($cachedArticles) {
            return $cachedArticles;
        }

        $result = $this->repo->getArticles($limit, $offset, $buscar);
        if (!$result) {
            return [];
        }

        $articles = [];
        while ($row = $result->fetch_assoc()) {
            $articles[] = $row;
        }
        $result->free();

        Cache::set($cacheKey, $articles);
        return $articles;
    }

    public function countArticles(string $buscar = ''): int
    {
        return $this->repo->countArticles($buscar);
    }

    /**
     * Obtener un artículo por ID (array asociativo) o null si no existe.
     * @param int $id
     * @return array|null
     */
    public function getArticleById(int $id): ?array
    {
        $cacheKey = 'article_' . $id;
        $cachedArticle = Cache::get($cacheKey);

        if ($cachedArticle) {
            return $cachedArticle;
        }

        $article = $this->repo->getArticleById($id);
        if ($article) {
            Cache::set($cacheKey, $article);
        }
        return $article;
    }

    /**
     * Obtener artículos por categoría como array asociativo.
     * @param string $category
     * @param int|null $limit
     * @param int $offset
     * @return array
     */
    public function getArticlesByCategoryArray(string $category, ?int $limit = null, int $offset = 0): array
    {
        $cacheKey = 'articles_by_category_' . md5($category . '_' . $limit . '_' . $offset);
        $cachedArticles = Cache::get($cacheKey);

        if ($cachedArticles) {
            return $cachedArticles;
        }

        $result = $this->repo->getArticlesByCategory($category, $limit, $offset);
        if (!$result) {
            return [];
        }
        $articles = [];
        while ($row = $result->fetch_assoc()) {
            $articles[] = $row;
        }
        $result->free();

        Cache::set($cacheKey, $articles);
        return $articles;
    }

    /**
     * Contar artículos por categoría.
     */
    public function countArticlesByCategory(string $category): int
    {
        return $this->repo->countArticlesByCategory($category);
    }

    /**
     * Contar artículos de un usuario.
     */
    public function countArticlesByUser(int $userId): int
    {
        return $this->repo->countArticlesByUser($userId);
    }

    /**
     * Obtener artículos de un usuario como array.
     */
    public function getArticlesByUserArray(int $userId, int $limit, int $offset): array
    {
        $result = $this->repo->getArticlesByUser($userId, $limit, $offset);
        if (!$result) {
            return [];
        }
        $articles = [];
        while ($row = $result->fetch_assoc()) {
            $articles[] = $row;
        }
        $result->free();
        return $articles;
    }

    /**
     * Crear un artículo.
     */
    public function createArticle(string $title, string $article, string $image, string $category, int $userId): bool
    {
        $success = $this->repo->insertArticle($title, $article, $image, $category, $userId);
        if ($success) {
            // Clear all caches related to article lists
            Cache::clearAll();
        }
        return $success;
    }

    /**
     * Actualizar un artículo.
     */
    public function updateArticle(int $id, string $title, string $article, ?string $image, string $category): bool
    {
        $success = $this->repo->updateArticle($id, $title, $article, $image, $category);
        if ($success) {
            // Clear relevant caches
            Cache::forget('article_' . $id); // Invalidate specific article
            Cache::clearAll(); // Invalidate all article lists and category specific lists
        }
        return $success;
    }

    /**
     * Eliminar un artículo (opcionalmente validando el userId).
     */
    public function deleteArticle(int $id, ?int $userId = null): bool
    {
        // Before deleting, get the category to invalidate its cache
        $article = $this->repo->getArticleById($id);
        $category = $article['category'] ?? null;

        $success = $this->repo->deleteArticle($id, $userId);
        if ($success) {
            // Clear relevant caches
            Cache::forget('article_' . $id); // Invalidate specific article
            Cache::clearAll(); // Invalidate all article lists and category specific lists
        }
        return $success;
    }
}
