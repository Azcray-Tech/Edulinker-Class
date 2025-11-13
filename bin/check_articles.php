<?php
// Script CLI para validar ArticleRepository / ArticleService
require_once __DIR__ . '/../vendor/autoload.php';

use App\Article\ArticleService;

echo "== Comprobación rápida de Articles API ==\n";

try {
    $service = new ArticleService();

    $total = $service->countArticles();
    echo "Total de artículos: " . $total . "\n";

    $sample = $service->getArticlesArray(5, 0);
    echo "Mostrando hasta 5 artículos:\n";
    if (empty($sample)) {
        echo "(No hay artículos o la consulta devolvió vacío)\n";
    } else {
        foreach ($sample as $a) {
            printf("- [%d] %s (%s)\n", $a['id'] ?? 0, $a['title'] ?? '(sin título)', $a['date'] ?? '(sin fecha)');
        }
    }

    echo "== FIN ==\n";
} catch (\Throwable $e) {
    echo "Error al ejecutar ArticleService: " . $e->getMessage() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
