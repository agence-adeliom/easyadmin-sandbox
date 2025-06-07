<?php

declare(strict_types=1);

namespace App\Tests\EasyMediaBundle\EventListener;

use Adeliom\EasyMediaBundle\Entity\Folder;
use Adeliom\EasyMediaBundle\Entity\Media;
use Adeliom\EasyMediaBundle\EventListener\DoctrineMappingListener;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use PHPUnit\Framework\TestCase;

class DoctrineMappingListenerTest extends TestCase
{
    public function testLoadClassMetadataAddsAssociations(): void
    {
        $folderMetadata = new ClassMetadata(Folder::class);
        $mediaMetadata = new ClassMetadata(Media::class);
        $listener = new DoctrineMappingListener(Media::class, Folder::class);
        $em = $this->createMock(\Doctrine\ORM\EntityManagerInterface::class);

        $listener->loadClassMetadata(new LoadClassMetadataEventArgs($folderMetadata, $em));
        self::assertArrayHasKey('parent', $folderMetadata->associationMappings);
        self::assertArrayHasKey('children', $folderMetadata->associationMappings);
        self::assertArrayHasKey('medias', $folderMetadata->associationMappings);

        $listener->loadClassMetadata(new LoadClassMetadataEventArgs($mediaMetadata, $em));
        self::assertArrayHasKey('folder', $mediaMetadata->associationMappings);
    }
}
