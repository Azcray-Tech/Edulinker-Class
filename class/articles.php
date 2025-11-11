<?php
class Articles
{

    private $id;
    private $title;
    private $image;
    private $content;
    private $date;
    private $author;
    private $category;

    public function __construct($id, $title, $image, $content, $date, $author, $category)
    {
        $this->id = $id;
        $this->title = $title;
        $this->image = $image;
        $this->content = $content;
        $this->date = $date;
        $this->author = $author;
        $this->category = $category;
    }

    // Getters and Setters

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }

    // Metodos de mi clase Articles

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
    public function insertarArticulo($conexion, $titulo, $contenido, $imagen, $categoria, $usuario)
    {
        // Intentar delegar en ArticleService
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
            error_log('ArticleService insertarArticulo (class) error: ' . $e->getMessage());
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
    public function obtenerArticuloPorId($conexion, $article_id, $user_id)
    {
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
            error_log('ArticleRepository obtenerArticuloPorId (class) error: ' . $e->getMessage());
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
    public function obtenerArticulosPaginados($conexion, $limite, $offset)
    {
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
            error_log('ArticleRepository obtenerArticulosPaginados (class) error: ' . $e->getMessage());
        }

        $sql = "SELECT 
                a.id,
                a.title,
                a.date,
                a.image,
                a.article,
                u.username,
                a.category
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
    public function contarArticulos($conexion)
    {
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
            error_log('ArticleService contarArticulos (class) error: ' . $e->getMessage());
        }

        $sql = "SELECT COUNT(*) AS total FROM articles";
        $result = $conexion->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    /**
     * Actualiza un artículo existente en la base de datos.
     * @param mysqli $conexion Conexión a la base de datos.
     * @param int $id ID del artículo a actualizar.
     * @param string $titulo Título del artículo.
     * @param string $contenido Contenido del artículo.
     * @param string $imagen Nombre del archivo de la imagen de portada.
     * @param string $categoria Categoría del artículo.
     * @return bool True si la actualización fue exitosa, false en caso contrario.
     */
    public function actualizarArticulo($conexion, $id, $titulo, $contenido, $imagen, $categoria)
    {
        try {
            if (!class_exists(\App\Article\ArticleService::class)) {
                if (file_exists(__DIR__ . "/../vendor/autoload.php")) {
                    require_once __DIR__ . "/../vendor/autoload.php";
                }
            }

            if (class_exists(\App\Article\ArticleService::class)) {
                $service = new \App\Article\ArticleService();
                // Si se quiere preservar la imagen existente, pasar null cuando corresponda
                $img = $imagen ?: null;
                return $service->updateArticle((int)$id, $titulo, $contenido, $img, $categoria);
            }
        } catch (\Throwable $e) {
            error_log('ArticleService actualizarArticulo (class) error: ' . $e->getMessage());
        }

        $sql = "UPDATE articles SET title=?, article=?, image=?, category=? WHERE id=?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ssssi", $titulo, $contenido, $imagen, $categoria, $id);
        $resultado = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $resultado;
    }

    /**
     * Elimina un artículo de la base de datos.
     * @param mysqli $conexion Conexión a la base de datos.
     * @param int $article_id ID del artículo a eliminar.
     * @param int $user_id ID del usuario que creó el artículo.
     * @return bool True si se eliminó correctamente, false en caso contrario.
     * @throws Exception Si ocurre un error al preparar o ejecutar la consulta.
     */
    public function eliminarArticulo($conexion, $article_id, $user_id)
    {
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
            error_log('ArticleService eliminarArticulo (class) error: ' . $e->getMessage());
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
    public function buscarArticulos($conexion, $search)
    {
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
            error_log('ArticleService buscarArticulos (class) error: ' . $e->getMessage());
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

    /**
     * Obtiene los artículos con paginación y búsqueda.
     *
     * @param mysqli $conexion Conexión a la base de datos.
     * @param string $buscar Término de búsqueda.
     * @param int $paginaActual Página actual.
     * @param int $articulosPorPagina Número de artículos por página.
     * @return array Lista de artículos.
     */
    public function obtenerArticulosConBusqueda($conexion, $buscar, $paginaActual, $articulosPorPagina)
    {
        $offset = ($paginaActual - 1) * $articulosPorPagina;
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
            error_log('ArticleService obtenerArticulosConBusqueda (class) error: ' . $e->getMessage());
        }

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
    public function contarArticulosConFiltro($conexion, $buscar)
    {
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

    /**
     * Muestra un artículo en la página.
     * @param array $row Datos del artículo.
     * @param string $seguirLeyendo Texto para el botón "Seguir leyendo".
     * @return void 
     */
    public function mostrarArticulo($row, $seguirLeyendo)
    {
        $fechaPublicacion = date("d/m/Y", strtotime($row['date']));
        $categoriaLink = VIEWS_URL . "articles/articles_category.php?id=" . $row['category'];
        $articuloLink = VIEWS_URL . "articles/articles.php?id=" . $row['id'];
        $imagenSrc = UPLOADS_URL . "articles/cover/" . $row['image'];

        echo '<h1 class="fw-bolder mb-3">' . htmlspecialchars($row['title']) . '</h1>';
        echo '<figure class="mb-4"><img class="img-fluid rounded" width="1000px" src="' . htmlspecialchars($imagenSrc) . '" alt="Artículo" /></figure>';
        echo '<div class="text-muted fst-italic mb-2">Publicado el ' . htmlspecialchars($fechaPublicacion) . ' por ' . htmlspecialchars($row['username']) . '</div>';
        echo '<a class="badge bg-primary text-decoration-none link-light mb-2" href="' . htmlspecialchars($categoriaLink) . '">' . htmlspecialchars($row['category']) . '</a>';
        echo '<section class="mb-5">';
        echo '    <p style="text-align: justify;" class="fs-5 mb-4">' . strip_tags(cortarTexto($row['article'])) . '</p>';
        echo '    <a class="btn bg-primary text-decoration-none link-light mb-2" href="' . htmlspecialchars($articuloLink) . '">' . htmlspecialchars($seguirLeyendo) . '</a>';
        echo '</section>';
    }
}
