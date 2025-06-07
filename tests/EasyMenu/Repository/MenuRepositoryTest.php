<?php
declare(strict_types=1);

namespace App\Tests\EasyMenu\Repository;

use App\Entity\EasyMenu\Menu;
use App\Entity\EasyMenu\MenuItem;
use App\Tests\EasyMenu\DoctrineOrmTestCase;

final class MenuRepositoryTest extends DoctrineOrmTestCase
{
    protected function getMetadata(): array
    {
        return [
            $this->entityManager->getClassMetadata(Menu::class),
            $this->entityManager->getClassMetadata(MenuItem::class),
        ];
    }

    public function testSetConfigUpdatesProperties(): void
    {
        $repo = $this->entityManager->getRepository(Menu::class);
        $repo->setConfig(['enabled' => true, 'ttl' => 3600]);

        $ref = new \ReflectionClass($repo);
        $enabled = $ref->getProperty('cacheEnabled');
        $enabled->setAccessible(true);
        $ttl = $ref->getProperty('cacheTtl');
        $ttl->setAccessible(true);

        self::assertTrue($enabled->getValue($repo));
        self::assertSame(3600, $ttl->getValue($repo));
    }
}
