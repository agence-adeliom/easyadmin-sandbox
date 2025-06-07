<?php
declare(strict_types=1);

namespace App\Tests\Fixtures;

use Adeliom\EasyCommonBundle\Enum\ThreeStateStatusEnum;
use App\Entity\EasyPage\Page;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PageFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $homepage = new Page();
        $homepage->setName("Page d'accueil");
        $homepage->setSlug('page-daccueil');
        $homepage->setState(ThreeStateStatusEnum::PUBLISHED);
        $homepage->setTemplate('homepage');
        $manager->persist($homepage);

        $child = new Page();
        $child->setName('Child page');
        $child->setSlug('child-page');
        $child->setState(ThreeStateStatusEnum::PUBLISHED);
        $child->setParent($homepage);
        $manager->persist($child);

        $sub = new Page();
        $sub->setName('Sub Child page');
        $sub->setSlug('sub-child-page');
        $sub->setState(ThreeStateStatusEnum::PUBLISHED);
        $sub->setParent($child);
        $manager->persist($sub);

        $manager->flush();
    }
}
