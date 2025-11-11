<?php
namespace App\Article;

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
        $result = $this->repo->getArticles($limit, $offset, $buscar);
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
        return $this->repo->getArticleById($id);
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
        $result = $this->repo->getArticlesByCategory($category, $limit, $offset);
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
        return $this->repo->insertArticle($title, $article, $image, $category, $userId);
    }

    /**
     * Actualizar un artículo.
     */
    public function updateArticle(int $id, string $title, string $article, ?string $image, string $category): bool
    {
        return $this->repo->updateArticle($id, $title, $article, $image, $category);
    }

    /**
     * Eliminar un artículo (opcionalmente validando el userId).
     */
    public function deleteArticle(int $id, ?int $userId = null): bool
    {
        return $this->repo->deleteArticle($id, $userId);
    }
}
