<?php

declare(strict_types=1);

namespace App\Tests\EasyMediaBundle\EventListener;

use Adeliom\EasyMediaBundle\Entity\Media;
use Adeliom\EasyMediaBundle\EventListener\MediaSubscriber;
use Adeliom\EasyMediaBundle\Service\EasyMediaManager;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class MediaSubscriberTest extends TestCase
{
    public function testPreUpdateMovesMedia(): void
    {
        $media = new Media();
        $media->setName('file');
        $manager = $this->createMock(EasyMediaManager::class);
        $manager->expects($this->once())->method('move');

        $subscriber = new MediaSubscriber($manager);
        $changes = ['folder' => [null, null]];
        $args = new PreUpdateEventArgs($media, $this->createMock(\Doctrine\ORM\EntityManagerInterface::class), $changes);
        $subscriber->preUpdate($args);
    }
}
