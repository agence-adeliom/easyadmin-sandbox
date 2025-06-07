<?php
declare(strict_types=1);

namespace App\Tests;

use App\Tests\Fixtures\PageFixtures;
use App\Tests\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PageLoadTest extends WebTestCase
{
    use FixturesTrait;

    private \Symfony\Bundle\FrameworkBundle\KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->loadFixtures([new PageFixtures()]);
    }
    public function testHomepageLoads(): void
    {
        $this->client->followRedirects(true);
        $this->client->request('GET', '/');

        self::assertResponseIsSuccessful();
    }

    public function testChildPageLoads(): void
    {
        $this->client->followRedirects(true);
        $this->client->request('GET', '/child-page/');

        self::assertResponseIsSuccessful();
    }

    public function testSubChildPageLoads(): void
    {
        $this->client->followRedirects(true);
        $this->client->request('GET', '/child-page/sub-child-page/');

        self::assertResponseIsSuccessful();
    }
}
