<?php
declare(strict_types=1);

namespace App\Tests\UserBundle\Fixture;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\EasyAdmin\User;

class UserFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('user@example.com');
        $user->setPassword('password');
        $user->setRoles(['ROLE_USER']);

        $manager->persist($user);
        $manager->flush();
    }
}
