<?php

declare(strict_types=1);

namespace App\Tests\EasyMediaBundle\Exception;

use Adeliom\EasyMediaBundle\Exception\AlreadyExist;
use Adeliom\EasyMediaBundle\Exception\BaseException;
use Adeliom\EasyMediaBundle\Exception\ExtNotAllowed;
use Adeliom\EasyMediaBundle\Exception\FolderAlreadyExist;
use Adeliom\EasyMediaBundle\Exception\FolderNotExist;
use Adeliom\EasyMediaBundle\Exception\NoFile;
use Adeliom\EasyMediaBundle\Exception\ProviderNotFound;
use PHPUnit\Framework\TestCase;

class ExceptionTest extends TestCase
{
    public function testExceptionsExtendBase(): void
    {
        $classes = [
            AlreadyExist::class,
            ExtNotAllowed::class,
            FolderAlreadyExist::class,
            FolderNotExist::class,
            NoFile::class,
            ProviderNotFound::class,
        ];

        foreach ($classes as $class) {
            $exception = new $class();
            self::assertInstanceOf(BaseException::class, $exception);
        }
    }
}
