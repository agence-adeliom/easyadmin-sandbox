<?php
declare(strict_types=1);

namespace App\Tests\EasyMenu\Repository;

use App\Entity\EasyMenu\Menu;
use App\Entity\EasyMenu\MenuItem;
use App\Tests\EasyMenu\DoctrineOrmTestCase;

final class MenuItemRepositoryTest extends DoctrineOrmTestCase
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
        $repo = $this->entityManager->getRepository(MenuItem::class);
        $repo->setConfig(['enabled' => true, 'ttl' => 1800]);

        $ref = new \ReflectionClass($repo);
        $enabled = $ref->getProperty('cacheEnabled');
        $enabled->setAccessible(true);
        $ttl = $ref->getProperty('cacheTtl');
        $ttl->setAccessible(true);

        self::assertTrue($enabled->getValue($repo));
        self::assertSame(1800, $ttl->getValue($repo));
    }
}
