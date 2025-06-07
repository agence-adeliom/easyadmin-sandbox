<?php

declare(strict_types=1);

namespace App\Tests\EasyEditorBundle\Twig;

use Adeliom\EasyEditorBundle\Twig\EasyBlockExtension;
use PHPUnit\Framework\TestCase;
use Twig\TwigFunction;

final class EasyBlockExtensionTest extends TestCase
{
    public function testFunctions(): void
    {
        $ext = new EasyBlockExtension();
        $functions = $ext->getFunctions();
        self::assertCount(2, $functions);
        self::assertContainsOnlyInstancesOf(TwigFunction::class, $functions);
        self::assertSame('easy_editor_block', $functions[0]->getName());
        self::assertSame('easy_editor_assets', $functions[1]->getName());
    }
}
