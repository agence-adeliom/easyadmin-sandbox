<?php

declare(strict_types=1);

namespace App\Tests\EasyMediaBundle\EventListener;

use Adeliom\EasyMediaBundle\Entity\Folder;
use Adeliom\EasyMediaBundle\EventListener\FolderSubscriber;
use Adeliom\EasyMediaBundle\Service\EasyMediaManager;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class FolderSubscriberTest extends TestCase
{
    public function testPreUpdateMovesFolder(): void
    {
        $folder = new Folder();
        $folder->setName('old');
        $manager = $this->createMock(EasyMediaManager::class);
        $manager->expects($this->once())->method('move');

        $subscriber = new FolderSubscriber($manager);
        $changes = ['parent' => [null, null]];
        $args = new PreUpdateEventArgs($folder, $this->createMock(\Doctrine\ORM\EntityManagerInterface::class), $changes);
        $subscriber->preUpdate($args);
    }
}
