<?php

declare(strict_types=1);

namespace App\Tests\EasyFieldsBundle\Controller;

use Adeliom\EasyFieldsBundle\Controller\OembedController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class OembedControllerTest extends TestCase
{
    public function testIndexWithMissingUrl(): void
    {
        $controller = new OembedController();
        $this->expectException(BadRequestException::class);
        $controller->index(new Request());
    }

    public function testIndexWithInvalidUrl(): void
    {
        $controller = new OembedController();
        $this->expectException(BadRequestException::class);
        $controller->index(new Request(['url' => 'invalid']));
    }
}
