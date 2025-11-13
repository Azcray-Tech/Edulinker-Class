<?php
include_once(__DIR__ . "/../lib/common.php");
include_once(__DIR__ . "/../lib/articles.php");

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    $resultados = [];
    try {
        if (!class_exists('\App\Article\ArticleService')) {
            if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
                require_once __DIR__ . '/../vendor/autoload.php';
            }
        }
        $service = new \App\Article\ArticleService();
        $resultados = $service->getArticlesArray(100, 0, $search);
    } catch (\Throwable $e) {
        error_log('ArticleService search error (controller): ' . $e->getMessage());
        $resultados = buscarArticulos($conexion, $search);
    }
} else {
    $resultados = [];
}

include_once(__DIR__ . "/../views/search_results.php");
?>
