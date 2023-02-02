<?php

declare(strict_types=1);

/*
 *  * This file has been edited by Adeliom.
 *  * Adeliom team <contact@adeliom.com>
 */

namespace App\DataFixtures;

use Adeliom\EasyMediaBundle\Exception\AlreadyExist;
use League\Flysystem\FilesystemException;
use League\Flysystem\UnableToCopyFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\AsciiSlugger;

trait MediaHelpers
{
    public function createMedia($folderPath = 'pages/home', $fileName = 'cta-market-col1.jpeg')
    {
        $projectDir = $this->kernel->getProjectDir();
        $manager = $this->easyMediaManager;

        try {
            return $manager->createMedia(
                sprintf('%s/var/fixtures_medias/%s/%s', $projectDir, $folderPath, $fileName),
                $folderPath,
                $fileName
            );
        } catch (AlreadyExist|FolderAlreadyExist|FileException|UnableToCopyFile) {}

        return null;
    }
}
