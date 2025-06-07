<?php
declare(strict_types=1);

namespace App\Tests\EasyFaq;

use App\Tests\Fixtures\FaqFixtures;
use App\Tests\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RepositoryTest extends KernelTestCase
{
    use FixturesTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->loadFixtures([new FaqFixtures()]);
    }

    public function testCategoryRepository(): void
    {
        $repo = self::getContainer()->get('easy_faq.category.repository');
        $category = $repo->getBySlug('faq-category');
        self::assertNotNull($category);
        self::assertSame('faq-category', $category->getSlug());
        $all = $repo->getPublished();
        self::assertGreaterThanOrEqual(1, count($all));
    }

    public function testEntryRepository(): void
    {
        $catRepo = self::getContainer()->get('easy_faq.category.repository');
        $category = $catRepo->getBySlug('faq-category');

        $repo = self::getContainer()->get('easy_faq.entry.repository');
        $entry = $repo->getBySlug('faq-entry', $category);
        self::assertNotNull($entry);
        self::assertSame('faq-entry', $entry->getSlug());
        $all = $repo->getByCategory($category);
        self::assertGreaterThanOrEqual(1, count($all));
    }
}
