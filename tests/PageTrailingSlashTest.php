<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PageTrailingSlashTest extends WebTestCase
{
    /**
     * @dataProvider pageProvider
     */
    public function testPageResponseIsSuccessful(string $url): void
    {
        $client = static::createClient();
        $client->followRedirects(true);
        $client->request('GET', $url);
        self::assertResponseIsSuccessful('Erreur sur l\'URL . ' . $url);
    }

    /**
     * @return string[][]
     */
    public function pageProvider(): array
    {
        return [
            ['https://easyadmin-sandbox.lndo.site/'],
            ['https://easyadmin-sandbox.lndo.site/page-daccueil/'],
            ['https://easyadmin-sandbox.lndo.site/child-page/'],
//            ['https://easyadmin-sandbox.lndo.site/test-page/'],
        ];
    }
}
