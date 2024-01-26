<?php

declare(strict_types=1);

namespace App\Services\AltGenerator;

use Adeliom\EasyMediaBundle\Entity\Media;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\HttpFoundation\File\File;

#[AsAlias(id: 'fakerAltGenerator', public: true)]
class FakerAltGenerator implements AltGeneratorInterface
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function generate(Media $entity, null|string|File $source): string
    {
        return $this->faker->realTextBetween(minNbChars: 20, maxNbChars: 50);
    }
}
