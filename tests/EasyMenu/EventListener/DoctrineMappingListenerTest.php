<?php
declare(strict_types=1);

namespace App\Tests\EasyMenu\EventListener;

use Adeliom\EasyMenuBundle\Entity\MenuEntity;
use Adeliom\EasyMenuBundle\Entity\MenuItemEntity;
use Adeliom\EasyMenuBundle\EventListener\DoctrineMappingListener;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use PHPUnit\Framework\TestCase;

final class DoctrineMappingListenerTest extends TestCase
{
    public function testRelationsAreAdded(): void
    {
        $listener = new DoctrineMappingListener(MenuEntity::class, MenuItemEntity::class);
        $em = $this->createMock(\Doctrine\ORM\EntityManagerInterface::class);
        $metadata = new ClassMetadata(MenuItemEntity::class);
        $event = new LoadClassMetadataEventArgs($metadata, $em);

        $listener->loadClassMetadata($event);

        self::assertTrue($metadata->hasAssociation('menu'));
        self::assertTrue($metadata->hasAssociation('parent'));
        self::assertTrue($metadata->hasAssociation('children'));
    }
}
