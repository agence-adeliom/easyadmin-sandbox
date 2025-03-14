<?php

namespace App\DataFixtures;

use Adeliom\EasyAdminUserBundle\Entity\User as UserAlias;
use Adeliom\EasyMediaBundle\Service\EasyMediaManager;
use App\DataFixtures\MediaHelpers;
use App\Entity\EasyAdmin\User;
use App\Entity\EasyPage\Page;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    use MediaHelpers;

    public function __construct(private UserPasswordHasherInterface $hasher, private KernelInterface $kernel, private EasyMediaManager $easyMediaManager)
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

        $homepage = new Page();
        $homepage->setName("Page d'accueil");
        $homepage->setSlug('page-daccueil');
        $homepage->setState('published');
        $homepage->setTemplate('homepage');

        $manager->persist($homepage);

        $childPage = new Page();
        $childPage->setName("Child page");
        $childPage->setSlug('child-page');
        $childPage->setState('published');
        $childPage->setParent($homepage);

        $manager->persist($childPage);

        $childSubPage = new Page();
        $childSubPage->setName("Sub Child page");
        $childSubPage->setSlug('sub-child-page');
        $childSubPage->setState('published');
        $childSubPage->setParent($childPage);

        $manager->persist($childSubPage);

        $testPage = new Page();
        $testPage->setName("Test page");
        $testPage->setSlug('test-page');
        $testPage->setState('published');

        $manager->persist($testPage);

        $this->createMedia('logos', 'psa.svg');
        $this->createMedia('contact', 'join-1.png');
        $this->createMedia('blog', 'catalog-1.jpg');
        $this->createMedia('solutions', 'header-tmp-2.jpeg');
        $this->createMedia('solutions/logos', 'leshop.svg');
        $this->createMedia('solutions', 'header-tmp-1.jpeg');
        $this->createMedia('media', 'textMediaMosaic_1.png');
        $this->createMedia('solutions/advango', 'argument-4.jpeg');
        $this->createMedia('solutions/hexagourmet', 'header-tmp-3.jpeg');
        $this->createMedia('solutions/files', 'dummy.pdf');
        $this->createMedia('solutions/shoppa', 'shoppa-tab-1-tmp.jpeg');
        $this->createMedia('corporate', 'helfrich35ans.jpeg');

        $manager->flush();
    }
}
