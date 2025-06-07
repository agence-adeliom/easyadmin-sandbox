<?php
declare(strict_types=1);

namespace App\Tests\EasyMenu\EventListener;

use Adeliom\EasyMenuBundle\Entity\MenuEntity;
use Adeliom\EasyMenuBundle\Entity\MenuItemEntity;
use Adeliom\EasyMenuBundle\EventListener\MenuCreationListener;
use PHPUnit\Framework\TestCase;

final class MenuCreationListenerTest extends TestCase
{
    public function testPrePersistAddsRootItem(): void
    {
        $listener = new MenuCreationListener(MenuEntity::class, MenuItemEntity::class);
        $menu = new class() extends MenuEntity {};

        $listener->prePersist($menu);

        self::assertCount(1, $menu->getItems());
        $item = $menu->getItems()->first();
        self::assertSame('Root', $item->getName());
        self::assertSame($menu, $item->getMenu());
    }
}
