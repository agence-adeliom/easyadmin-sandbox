<?php

declare(strict_types=1);

namespace App\Services\AltGenerator;

use Adeliom\EasyMediaBundle\Entity\Media;
use Symfony\Component\HttpFoundation\File\File;

interface AltGeneratorInterface
{
    public function generate(Media $entity, null | string | File $source): string;
}
