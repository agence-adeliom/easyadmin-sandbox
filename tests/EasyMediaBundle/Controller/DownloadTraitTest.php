<?php

declare(strict_types=1);

namespace App\Tests\EasyMediaBundle\Controller;

use Adeliom\EasyMediaBundle\Controller\Module\Download;
use League\Flysystem\FilesystemOperator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadTraitTest extends TestCase
{
    public function testDownloadFilesReturnsResponse(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $dummy = new class() {
            use Download {
                downloadFiles as public;
                zipAndDownload as protectedZipAndDownload;
            }
            public FilesystemOperator $filesystem;
            protected function zipAndDownload($name, $list): StreamedResponse
            {
                return new StreamedResponse();
            }
        };
        $dummy->filesystem = $this->createMock(FilesystemOperator::class);
        $request = new Request();
        $dummy->downloadFiles($request);
    }
}
