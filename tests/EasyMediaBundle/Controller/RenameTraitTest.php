<?php

declare(strict_types=1);

namespace App\Tests\EasyMediaBundle\Controller;

use Adeliom\EasyMediaBundle\Controller\Module\Rename;
use Adeliom\EasyMediaBundle\Service\EasyMediaHelper;
use App\Tests\Fixtures\MediaFactory;
use Adeliom\EasyMediaBundle\Service\EasyMediaManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class RenameTraitTest extends TestCase
{
    public function testRenameItemReturnsJson(): void
    {
        $dummy = new class() {
            use Rename;
            public EasyMediaManager $manager;
            public EasyMediaHelper $helper;
            public EventDispatcherInterface $eventDispatcher;
            public TranslatorInterface $translator;
        };

        $dummy->manager = $this->createMock(EasyMediaManager::class);
        $dummy->manager->method('getMedia')->willReturnCallback(function () {
            return MediaFactory::createMedia();
        });
        $dummy->helper = $this->createMock(EasyMediaHelper::class);
        $dummy->helper->method('cleanName')->willReturn('new');
        $dummy->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $dummy->translator = $this->createMock(TranslatorInterface::class);

        $request = new Request([], [], [], [], [], [], json_encode(['file' => ['id' => 1, 'type' => 'file'], 'new_filename' => 'new']));
        $response = $dummy->renameItem($request);
        self::assertInstanceOf(JsonResponse::class, $response);
    }
}
