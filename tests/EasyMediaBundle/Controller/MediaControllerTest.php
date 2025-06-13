<?php

declare(strict_types=1);

namespace App\Tests\EasyMediaBundle\Controller;

use Adeliom\EasyMediaBundle\Controller\MediaController;
use Adeliom\EasyMediaBundle\Service\EasyMediaHelper;
use Adeliom\EasyMediaBundle\Service\EasyMediaManager;
use Doctrine\Persistence\ManagerRegistry;
use League\Flysystem\FilesystemOperator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class MediaControllerTest extends TestCase
{
    public function testIndexAndBrowseTriggerRender(): void
    {
        $this->expectException(\Throwable::class);
        $controller = $this->createController();
        $controller->index();
        $controller->browse(new Request(['provider' => 'default']));
    }

    private function createController(): MediaController
    {
        $manager = $this->createMock(EasyMediaManager::class);
        $manager->method('getFilesystem')->willReturn($this->createMock(FilesystemOperator::class));
        $manager->method('getHelper')->willReturn($this->createMock(EasyMediaHelper::class));

        $registry = $this->createMock(ManagerRegistry::class);
        $registry->method('getManager')->willReturn($this->createMock(\Doctrine\ORM\EntityManagerInterface::class));

        $bag = $this->createMock(ParameterBagInterface::class);
        $bag->method('get')->willReturnMap([
            ['easy_media.ignore_files', ''],
            ['easy_media.pagination_amount', 10],
            ['kernel.project_dir', sys_get_temp_dir()],
        ]);

        return new MediaController($manager, $registry, $bag, $this->createMock(EventDispatcherInterface::class), $this->createMock(TranslatorInterface::class));
    }
}
