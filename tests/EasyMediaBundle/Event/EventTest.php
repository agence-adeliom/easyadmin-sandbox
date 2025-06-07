<?php

declare(strict_types=1);

namespace App\Tests\EasyMediaBundle\Event;

use Adeliom\EasyMediaBundle\Entity\Media;
use Adeliom\EasyMediaBundle\Event\EasyMediaBeforeFileCreated;
use Adeliom\EasyMediaBundle\Event\EasyMediaBeforeSetMetas;
use Adeliom\EasyMediaBundle\Event\EasyMediaFileDeleted;
use Adeliom\EasyMediaBundle\Event\EasyMediaFileMoved;
use Adeliom\EasyMediaBundle\Event\EasyMediaFileRenamed;
use Adeliom\EasyMediaBundle\Event\EasyMediaFileSaved;
use Adeliom\EasyMediaBundle\Event\EasyMediaFileUploaded;
use Adeliom\EasyMediaBundle\Event\EasyMediaGenerateAlt;
use Adeliom\EasyMediaBundle\Event\EasyMediaGenerateAltGroup;
use Adeliom\EasyMediaBundle\Event\EasyMediaGenerateAllAlt;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class EventTest extends TestCase
{
    public function testBeforeFileCreated(): void
    {
        $event = new EasyMediaBeforeFileCreated('data', '/tmp', 'name');
        self::assertSame('data', $event->getData());
        self::assertSame('/tmp', $event->getFolderPath());
        self::assertSame('name', $event->getName());
    }

    public function testBeforeSetMetas(): void
    {
        $media = new Media();
        $event = new EasyMediaBeforeSetMetas($media, 'src', ['a' => 'b']);
        self::assertSame($media, $event->getEntity());
        self::assertSame('src', $event->getSource());
        self::assertSame(['a' => 'b'], $event->getMetas());
    }

    public function testFileDeleted(): void
    {
        $event = new EasyMediaFileDeleted("/tmp/file", true);
        self::assertNull($event->filePath);
        self::assertNull($event->isFolder);
    }
    public function testFileRenamed(): void
    {
        $event = new EasyMediaFileRenamed('old', 'new');
        self::assertNull($event->oldPath);
        self::assertNull($event->newPath);
    }

    public function testFileMovedAndSaved(): void
    {
        $moved = new EasyMediaFileMoved('old', 'new');
        self::assertInstanceOf(EasyMediaFileRenamed::class, $moved);

        $saved = new EasyMediaFileSaved('path', 'image/png');
        self::assertInstanceOf(EasyMediaFileUploaded::class, $saved);
    }

    public function testFileUploaded(): void
    {
        $event = new EasyMediaFileUploaded('/tmp/file', 'image/png');
        self::assertSame('/tmp/file', $event->getFilePath());
        self::assertSame('image/png', $event->getMimeType());
    }

    public function testGenerateAlt(): void
    {
        $media = new Media();
        $event = new EasyMediaGenerateAlt($media, 'path', 'alt');
        self::assertSame($media, $event->getEntity());
        self::assertSame('path', $event->getFilePath());
        self::assertSame('alt', $event->getAlt());
    }

    public function testGenerateAltGroup(): void
    {
        $event = new EasyMediaGenerateAltGroup([1,2]);
        self::assertSame([1,2], $event->getFiles());
    }

    public function testGenerateAllAlt(): void
    {
        $request = new Request();
        $event = new EasyMediaGenerateAllAlt($request);
        self::assertSame($request, $event->getRequest());
    }
}
