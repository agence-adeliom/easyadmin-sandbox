<?php
declare(strict_types=1);

namespace App\Tests\EasyFaq;

use App\Tests\Fixtures\FaqFixtures;
use App\Tests\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ControllerTest extends WebTestCase
{
    use FixturesTrait;

    public function testCategoryPage(): void
    {
        $client = static::createClient();
        $this->loadFixtures([new FaqFixtures()]);
        $client->request('GET', '/faq/faq-category');
        self::assertResponseIsSuccessful();
    }

    public function testEntryPage(): void
    {
        $client = static::createClient();
        $this->loadFixtures([new FaqFixtures()]);
        $client->request('GET', '/faq/faq-category/faq-entry');
        self::assertResponseIsSuccessful();
    }
}
