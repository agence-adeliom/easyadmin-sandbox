<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\EasyRedirect\Redirect;

class RedirectFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $redirect = new Redirect('/old-path', '/new-path');
        $manager->persist($redirect);
        $manager->flush();
    }
}
