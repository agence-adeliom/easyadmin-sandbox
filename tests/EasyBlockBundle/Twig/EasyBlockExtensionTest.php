<?php
declare(strict_types=1);

namespace App\Tests\EasyBlockBundle\Twig;

use Adeliom\EasyBlockBundle\Block\Helper;
use Adeliom\EasyBlockBundle\Twig\EasyBlockExtension;
use PHPUnit\Framework\TestCase;

class EasyBlockExtensionTest extends TestCase
{
    public function testFunctionsAreRegistered(): void
    {
        $helper = $this->createMock(Helper::class);
        $extension = new EasyBlockExtension($helper);
        $functions = $extension->getFunctions();

        $names = array_map(static fn($f) => $f->getName(), $functions);
        $this->assertContains('easy_block', $names);
        $this->assertContains('easy_block_assets', $names);
    }
}
