<?php

namespace App\DataFixtures;

use Adeliom\EasyAdminUserBundle\Entity\User as UserAlias;
use App\Entity\EasyAdmin\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EasyAdminUserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        // Super Admin
        $superAdmin = new User();
        $superAdmin->setFirstname('Super');
        $superAdmin->setLastname('Admin');
        $superAdmin->setEmail('super.admin@example.com');
        $superAdmin->setPassword($this->hasher->hashPassword($superAdmin, 'SuperAdmin123!'));
        $superAdmin->setRoles([UserAlias::SUPER_ADMIN]);
        $superAdmin->setEnabled(true);
        $manager->persist($superAdmin);

        // Regular Admin
        $admin = new User();
        $admin->setFirstname('John');
        $admin->setLastname('Admin');
        $admin->setEmail('admin@example.com');
        $admin->setPassword($this->hasher->hashPassword($admin, 'Admin123!'));
        $admin->setRoles([UserAlias::ADMIN]);
        $admin->setEnabled(true);
        $manager->persist($admin);

        // Regular User
        $user = new User();
        $user->setFirstname('Jane');
        $user->setLastname('User');
        $user->setEmail('user@example.com');
        $user->setPassword($this->hasher->hashPassword($user, 'User123!'));
        $user->setRoles(['ROLE_USER']);
        $user->setEnabled(true);
        $manager->persist($user);

        // Disabled User
        $disabledUser = new User();
        $disabledUser->setFirstname('Disabled');
        $disabledUser->setLastname('User');
        $disabledUser->setEmail('disabled@example.com');
        $disabledUser->setPassword($this->hasher->hashPassword($disabledUser, 'Disabled123!'));
        $disabledUser->setRoles(['ROLE_USER']);
        $disabledUser->setEnabled(false);
        $manager->persist($disabledUser);

        $manager->flush();
    }
}