<?php

declare(strict_types=1);

namespace App\Tests\EasyFieldsBundle\Twig;

use Adeliom\EasyFieldsBundle\Twig\OembedExtension;
use PHPUnit\Framework\TestCase;

class OembedExtensionTest extends TestCase
{
    public function testInvalidUrlReturnsNull(): void
    {
        $ext = new OembedExtension();
        $this->assertNull($ext->getCode('http://invalid')); 
        $this->assertNull($ext->getDimensions('http://invalid')); 
    }
}
