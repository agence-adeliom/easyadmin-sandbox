<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use Adeliom\EasyMediaBundle\Entity\Media;
use Adeliom\EasyMediaBundle\Entity\Folder;

final class MediaFactory
{
    public static function createFolder(string $name = 'folder', ?Folder $parent = null): Folder
    {
        $folder = new Folder();
        $folder->setName($name);
        if ($parent) {
            $folder->setParent($parent);
        }

        return $folder;
    }

    public static function createMedia(string $name = 'file', ?Folder $folder = null): Media
    {
        $media = new Media();
        $media->setName($name);
        $media->setFolder($folder);

        return $media;
    }
}
