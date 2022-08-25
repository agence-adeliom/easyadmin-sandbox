<?php

namespace App\DataFixtures;

use Adeliom\EasyAdminUserBundle\Entity\User as UserAlias;
use App\Entity\EasyAdmin\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setFirstname('John');
        $user->setLastname('Doe');
        $user->setEmail('admin@adeliom.com');
        $user->setPassword($this->hasher->hashPassword($user, 'admin'));
        $user->setRoles([UserAlias::SUPER_ADMIN]);
        $user->setEnabled(true);
        $manager->persist($user);

        $manager->flush();
    }
}
