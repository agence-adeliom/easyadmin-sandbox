<?php
declare(strict_types=1);

namespace App\Tests\EasyConfigBundle\Fixtures;

use App\Entity\EasyConfig\Config;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ConfigFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $text = (new Config())
            ->setKey('site_name')
            ->setName('Site name')
            ->setType('text')
            ->setValue('EasyAdmin');
        $manager->persist($text);

        $json = (new Config())
            ->setKey('settings')
            ->setName('Settings')
            ->setType('json')
            ->setValue(json_encode(['foo' => 'bar'], JSON_THROW_ON_ERROR));
        $manager->persist($json);

        $manager->flush();
    }
}
