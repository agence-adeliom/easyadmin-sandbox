<?php

declare(strict_types=1);

namespace App\Tests\EasyMediaBundle\Twig;

use Adeliom\EasyMediaBundle\Entity\Media;
use Adeliom\EasyMediaBundle\Service\EasyMediaHelper;
use Adeliom\EasyMediaBundle\Service\EasyMediaManager;
use Adeliom\EasyMediaBundle\Twig\EasyMediaRuntime;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Liip\ImagineBundle\Imagine\Filter\FilterManager;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

class EasyMediaRuntimeTest extends TestCase
{
    public function testGetMimeIcon(): void
    {
        $runtime = $this->createRuntime();
        self::assertSame('fa-file-image-o', $runtime->getMimeIcon('image/png'));
    }

    private function createRuntime(): EasyMediaRuntime
    {
        $manager = $this->createMock(EasyMediaManager::class);
        $manager->method('getMedia')->willReturn(new Media());
        $runtime = new EasyMediaRuntime($manager, $this->createMock(Environment::class), $this->createMock(FilterManager::class), $this->createMock(CacheManager::class));

        return $runtime;
    }
}
