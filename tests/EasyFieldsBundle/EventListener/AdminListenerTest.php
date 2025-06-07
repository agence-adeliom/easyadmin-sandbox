<?php

declare(strict_types=1);

namespace App\Tests\EasyFieldsBundle\EventListener;

use Adeliom\EasyFieldsBundle\EventListener\AdminListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class DummyKernel implements HttpKernelInterface
{
    public function handle(Request $request, int $type = self::MAIN_REQUEST, bool $catch = true): Response
    {
        return new Response();
    }
}

class AdminListenerTest extends TestCase
{
    public function testHeadersAreAdded(): void
    {
        $listener = new AdminListener();
        $request = new Request(['crudAction' => 'edit', 'crudControllerFqcn' => 'App\\Controller']);
        $response = new Response();
        $event = new ResponseEvent(new DummyKernel(), $request, HttpKernelInterface::MAIN_REQUEST, $response);
        $listener->onKernelResponse($event);
        $this->assertSame('edit', $response->headers->get('X-CRUD-ACTION'));
        $this->assertSame('App\\Controller', $response->headers->get('X-CRUD-CONTROLLER'));
    }
}
