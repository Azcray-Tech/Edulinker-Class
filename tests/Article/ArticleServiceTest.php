<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Article\ArticleService;

final class ArticleServiceTest extends TestCase
{
    public function testCountArticlesReturnsInt()
    {
        $service = new ArticleService();
        $total = $service->countArticles();
        $this->assertIsInt($total, 'countArticles should return an integer');
        $this->assertGreaterThanOrEqual(0, $total, 'total should be >= 0');
    }

    public function testGetArticlesArrayReturnsArray()
    {
        $service = new ArticleService();
        $articles = $service->getArticlesArray(5, 0);
        $this->assertIsArray($articles, 'getArticlesArray should return an array');
        // If there are results, each item must be an array with keys we expect
        if (!empty($articles)) {
            $first = $articles[0];
            $this->assertIsArray($first);
            $this->assertArrayHasKey('id', $first);
            $this->assertArrayHasKey('title', $first);
        }
    }
}
