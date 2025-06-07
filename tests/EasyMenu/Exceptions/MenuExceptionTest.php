<?php
declare(strict_types=1);

namespace App\Tests\EasyMenu\Exceptions;

use Adeliom\EasyMenuBundle\Exceptions\MenuNotFoundException;
use Adeliom\EasyMenuBundle\Exceptions\TemplateNotFoundException;
use PHPUnit\Framework\TestCase;

final class MenuExceptionTest extends TestCase
{
    public function testMenuNotFoundMessage(): void
    {
        $exception = new MenuNotFoundException('main');
        self::assertSame('Could not find menu with code "main".', $exception->getMessage());
    }

    public function testTemplateNotFoundMessage(): void
    {
        $exception = new TemplateNotFoundException('missing.html.twig');
        self::assertSame('Could not find template "missing.html.twig".', $exception->getMessage());
    }
}
