<?php
declare(strict_types=1);

namespace App\Tests\EasyFaq;

use Adeliom\EasyFaqBundle\Routing\FaqCategoryLoader;
use Adeliom\EasyFaqBundle\Routing\FaqEntryLoader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\RouteCollection;

class RoutingTest extends TestCase
{
    public function testCategoryLoaderAddsRoute(): void
    {
        $loader = new FaqCategoryLoader('Controller', 'Entity', $this->createMock(\Adeliom\EasyFaqBundle\Repository\CategoryRepository::class), ['root_path' => '/faq']);
        $routes = $loader->load('', 'easy_faq_category');
        self::assertInstanceOf(RouteCollection::class, $routes);
        self::assertTrue($routes->get('easy_faq_category_index') !== null);
        self::assertSame('/faq/{category}', $routes->get('easy_faq_category_index')->getPath());
    }

    public function testEntryLoaderAddsRoute(): void
    {
        $loader = new FaqEntryLoader('Controller', 'Entity', $this->createMock(\Adeliom\EasyFaqBundle\Repository\EntryRepository::class), ['root_path' => '/faq']);
        $routes = $loader->load('', 'easy_faq_entry');
        self::assertInstanceOf(RouteCollection::class, $routes);
        self::assertTrue($routes->get('easy_faq_entry_index') !== null);
        self::assertSame('/faq/{category}/{entry}', $routes->get('easy_faq_entry_index')->getPath());
    }
}
