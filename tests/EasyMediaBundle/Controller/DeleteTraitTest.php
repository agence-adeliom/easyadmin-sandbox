<?php

declare(strict_types=1);

namespace App\Tests\EasyMediaBundle\Controller;

use Adeliom\EasyMediaBundle\Controller\Module\Delete;
use Adeliom\EasyMediaBundle\Service\EasyMediaManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class DeleteTraitTest extends TestCase
{
    public function testDeleteItemReturnsJson(): void
    {
        $dummy = new class() {
            use Delete;
            public EasyMediaManager $manager;
            public EventDispatcherInterface $eventDispatcher;
            public TranslatorInterface $translator;
        };

        $dummy->manager = $this->createMock(EasyMediaManager::class);
        $dummy->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $dummy->translator = $this->createMock(TranslatorInterface::class);

        $request = new Request([], [], [], [], [], [], json_encode(['deleted_files' => []]));
        $response = $dummy->deleteItem($request);

        self::assertInstanceOf(JsonResponse::class, $response);
    }
}
