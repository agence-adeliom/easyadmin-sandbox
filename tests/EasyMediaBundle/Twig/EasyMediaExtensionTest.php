<?php

declare(strict_types=1);

namespace App\Tests\EasyMediaBundle\Twig;

use Adeliom\EasyMediaBundle\Service\EasyMediaManager;
use Adeliom\EasyMediaBundle\Twig\EasyMediaExtension;
use Liip\ImagineBundle\Imagine\Filter\FilterManager;
use PHPUnit\Framework\TestCase;

class EasyMediaExtensionTest extends TestCase
{
    public function testGetFiltersAndFunctions(): void
    {
        $extension = new EasyMediaExtension($this->createMock(EasyMediaManager::class), $this->createMock(FilterManager::class));
        self::assertNotEmpty($extension->getFilters());
        self::assertNotEmpty($extension->getFunctions());
    }
}
