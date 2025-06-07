<?php

declare(strict_types=1);

namespace App\Tests\EasyMediaBundle\Imagine;

use Adeliom\EasyMediaBundle\Imagine\Data\EasyMediaDataLoader;
use League\Flysystem\FilesystemOperator;
use Liip\ImagineBundle\Model\Binary;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mime\MimeTypesInterface;

class DataLoaderTest extends TestCase
{
    public function testFindReturnsBinary(): void
    {
        $filesystem = $this->createMock(FilesystemOperator::class);
        $filesystem->method('mimeType')->willReturn('image/png');
        $filesystem->method('read')->willReturn('content');
        $mime = $this->createMock(MimeTypesInterface::class);
        $mime->method('getExtensions')->willReturn(['png']);

        $loader = new EasyMediaDataLoader($filesystem, $mime);
        $binary = $loader->find('file');
        self::assertInstanceOf(Binary::class, $binary);
    }
}
