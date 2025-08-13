<?php

namespace App\DataFixtures;

use Adeliom\EasyAdminUserBundle\Entity\User as UserAlias;
use Adeliom\EasyCommonBundle\Enum\ThreeStateStatusEnum;
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

        // Homepage
        $homepage = new Page();
        $homepage->setName('Homepage');
        $homepage->setSlug('homepage');
        $homepage->setState(ThreeStateStatusEnum::PUBLISHED);
        $homepage->setTemplate('homepage');
        $manager->persist($homepage);

        // About Section
        $aboutPage = new Page();
        $aboutPage->setName('About Us');
        $aboutPage->setSlug('about');
        $aboutPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $aboutPage->setTemplate('default');
        $manager->persist($aboutPage);

        $aboutCompanyPage = new Page();
        $aboutCompanyPage->setName('Company History');
        $aboutCompanyPage->setSlug('company-history');
        $aboutCompanyPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $aboutCompanyPage->setParent($aboutPage);
        $aboutCompanyPage->setTemplate('default');
        $manager->persist($aboutCompanyPage);

        $aboutTeamPage = new Page();
        $aboutTeamPage->setName('Our Team');
        $aboutTeamPage->setSlug('our-team');
        $aboutTeamPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $aboutTeamPage->setParent($aboutPage);
        $aboutTeamPage->setTemplate('default');
        $manager->persist($aboutTeamPage);

        $aboutCareersPage = new Page();
        $aboutCareersPage->setName('Careers');
        $aboutCareersPage->setSlug('careers');
        $aboutCareersPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $aboutCareersPage->setParent($aboutPage);
        $aboutCareersPage->setTemplate('default');
        $manager->persist($aboutCareersPage);

        // Services Section
        $servicesPage = new Page();
        $servicesPage->setName('Services');
        $servicesPage->setSlug('services');
        $servicesPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $servicesPage->setTemplate('default');
        $manager->persist($servicesPage);

        $webDevPage = new Page();
        $webDevPage->setName('Web Development');
        $webDevPage->setSlug('web-development');
        $webDevPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $webDevPage->setParent($servicesPage);
        $webDevPage->setTemplate('default');
        $manager->persist($webDevPage);

        $symfonyDevPage = new Page();
        $symfonyDevPage->setName('Symfony Development');
        $symfonyDevPage->setSlug('symfony-development');
        $symfonyDevPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $symfonyDevPage->setParent($webDevPage);
        $symfonyDevPage->setTemplate('default');
        $manager->persist($symfonyDevPage);

        $reactDevPage = new Page();
        $reactDevPage->setName('React Development');
        $reactDevPage->setSlug('react-development');
        $reactDevPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $reactDevPage->setParent($webDevPage);
        $reactDevPage->setTemplate('default');
        $manager->persist($reactDevPage);

        $mobileDevPage = new Page();
        $mobileDevPage->setName('Mobile Development');
        $mobileDevPage->setSlug('mobile-development');
        $mobileDevPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $mobileDevPage->setParent($servicesPage);
        $mobileDevPage->setTemplate('default');
        $manager->persist($mobileDevPage);

        $iosDevPage = new Page();
        $iosDevPage->setName('iOS Development');
        $iosDevPage->setSlug('ios-development');
        $iosDevPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $iosDevPage->setParent($mobileDevPage);
        $iosDevPage->setTemplate('default');
        $manager->persist($iosDevPage);

        $androidDevPage = new Page();
        $androidDevPage->setName('Android Development');
        $androidDevPage->setSlug('android-development');
        $androidDevPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $androidDevPage->setParent($mobileDevPage);
        $androidDevPage->setTemplate('default');
        $manager->persist($androidDevPage);

        $consultingPage = new Page();
        $consultingPage->setName('Consulting');
        $consultingPage->setSlug('consulting');
        $consultingPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $consultingPage->setParent($servicesPage);
        $consultingPage->setTemplate('default');
        $manager->persist($consultingPage);

        $techConsultingPage = new Page();
        $techConsultingPage->setName('Technical Consulting');
        $techConsultingPage->setSlug('technical-consulting');
        $techConsultingPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $techConsultingPage->setParent($consultingPage);
        $techConsultingPage->setTemplate('default');
        $manager->persist($techConsultingPage);

        $strategyConsultingPage = new Page();
        $strategyConsultingPage->setName('Strategy Consulting');
        $strategyConsultingPage->setSlug('strategy-consulting');
        $strategyConsultingPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $strategyConsultingPage->setParent($consultingPage);
        $strategyConsultingPage->setTemplate('default');
        $manager->persist($strategyConsultingPage);

        // Portfolio Section
        $portfolioPage = new Page();
        $portfolioPage->setName('Portfolio');
        $portfolioPage->setSlug('portfolio');
        $portfolioPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $portfolioPage->setTemplate('default');
        $manager->persist($portfolioPage);

        $portfolioWebPage = new Page();
        $portfolioWebPage->setName('Web Projects');
        $portfolioWebPage->setSlug('web-projects');
        $portfolioWebPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $portfolioWebPage->setParent($portfolioPage);
        $portfolioWebPage->setTemplate('default');
        $manager->persist($portfolioWebPage);

        $ecommerceProjectPage = new Page();
        $ecommerceProjectPage->setName('E-commerce Platform');
        $ecommerceProjectPage->setSlug('ecommerce-platform');
        $ecommerceProjectPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $ecommerceProjectPage->setParent($portfolioWebPage);
        $ecommerceProjectPage->setTemplate('default');
        $manager->persist($ecommerceProjectPage);

        $cmsProjectPage = new Page();
        $cmsProjectPage->setName('CMS Solution');
        $cmsProjectPage->setSlug('cms-solution');
        $cmsProjectPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $cmsProjectPage->setParent($portfolioWebPage);
        $cmsProjectPage->setTemplate('default');
        $manager->persist($cmsProjectPage);

        $portfolioMobilePage = new Page();
        $portfolioMobilePage->setName('Mobile Apps');
        $portfolioMobilePage->setSlug('mobile-apps');
        $portfolioMobilePage->setState(ThreeStateStatusEnum::PUBLISHED);
        $portfolioMobilePage->setParent($portfolioPage);
        $portfolioMobilePage->setTemplate('default');
        $manager->persist($portfolioMobilePage);

        // Support Section
        $supportPage = new Page();
        $supportPage->setName('Support');
        $supportPage->setSlug('support');
        $supportPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $supportPage->setTemplate('default');
        $manager->persist($supportPage);

        $documentationPage = new Page();
        $documentationPage->setName('Documentation');
        $documentationPage->setSlug('documentation');
        $documentationPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $documentationPage->setParent($supportPage);
        $documentationPage->setTemplate('default');
        $manager->persist($documentationPage);

        $apiDocsPage = new Page();
        $apiDocsPage->setName('API Documentation');
        $apiDocsPage->setSlug('api-documentation');
        $apiDocsPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $apiDocsPage->setParent($documentationPage);
        $apiDocsPage->setTemplate('default');
        $manager->persist($apiDocsPage);

        $userGuidePage = new Page();
        $userGuidePage->setName('User Guide');
        $userGuidePage->setSlug('user-guide');
        $userGuidePage->setState(ThreeStateStatusEnum::PUBLISHED);
        $userGuidePage->setParent($documentationPage);
        $userGuidePage->setTemplate('default');
        $manager->persist($userGuidePage);

        $contactPage = new Page();
        $contactPage->setName('Contact');
        $contactPage->setSlug('contact');
        $contactPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $contactPage->setTemplate('contact');
        $manager->persist($contactPage);

        // Configuration display page
        $configPage = new Page();
        $configPage->setName('System Configuration');
        $configPage->setSlug('system-config');
        $configPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $configPage->setTemplate('config');
        $manager->persist($configPage);

        // Unpublished page for testing
        $draftPage = new Page();
        $draftPage->setName('Coming Soon');
        $draftPage->setSlug('coming-soon');
        $draftPage->setState(ThreeStateStatusEnum::UNPUBLISHED);
        $draftPage->setTemplate('default');
        $manager->persist($draftPage);

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
