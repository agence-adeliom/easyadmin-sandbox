<?php

declare(strict_types=1);

namespace App\Services;

use Adeliom\EasyMediaBundle\Entity\Media;

class AltGeneratorService
{
    public function generate(Media $entity): string
    {
        return 'generated alt text';
    }
}
