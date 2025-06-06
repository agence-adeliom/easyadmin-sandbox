<?php
declare(strict_types=1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PageLoadTest extends WebTestCase
{
    public function testHomepageLoads(): void
    {
        $client = static::createClient();
        $client->followRedirects(true);
        $client->request('GET', '/');

        self::assertResponseIsSuccessful();
    }

    public function testChildPageLoads(): void
    {
        $client = static::createClient();
        $client->followRedirects(true);
        $client->request('GET', '/child-page/');

        self::assertResponseIsSuccessful();
    }

    public function testSubChildPageLoads(): void
    {
        $client = static::createClient();
        $client->followRedirects(true);
        $client->request('GET', '/child-page/sub-child-page/');

        self::assertResponseIsSuccessful();
    }
}
