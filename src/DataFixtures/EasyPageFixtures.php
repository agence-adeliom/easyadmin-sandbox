<?php

namespace App\DataFixtures;

use Adeliom\EasyAdminUserBundle\Entity\User as UserAlias;
use Adeliom\EasyCommonBundle\Enum\ThreeStateStatusEnum;
use Adeliom\EasyMediaBundle\Service\EasyMediaManager;
use App\Blocks\HeaderType;
use App\DataFixtures\MediaHelpers;
use App\Entity\EasyAdmin\User;
use App\Entity\EasyPage\Page;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EasyPageFixtures extends Fixture
{
    use MediaHelpers;

    public function __construct(private UserPasswordHasherInterface $hasher, private KernelInterface $kernel, private EasyMediaManager $easyMediaManager)
    {
    }

    public function load(ObjectManager $manager): void
    {
        // Homepage
        $homepage = new Page();
        $homepage->setName('Homepage');
        $homepage->setSlug('homepage');
        $homepage->setState(ThreeStateStatusEnum::PUBLISHED);
        $homepage->setTemplate('homepage');
        $homepage->getSEO()->title = 'Adeliom EasyAdmin Sandbox - Symfony Bundle Ecosystem Demo';
        $homepage->getSEO()->description = 'Explore the comprehensive Adeliom EasyAdmin Sandbox showcasing our complete Symfony bundle ecosystem including EasyBlog, EasyMedia, EasyFAQ, EasyConfig, and more. Perfect for developers and businesses.';
        $homepage->getSEO()->keywords = 'Adeliom, EasyAdmin, Symfony bundles, PHP framework, web development, CMS, blog system, media management, FAQ system, configuration management';
        $homepage->getSEO()->cannonical = 'https://example.com/';
        $homepage->getSEO()->sitemap = true;
        $homepage->getSEO()->robots = ['index', 'follow'];
        $manager->persist($homepage);

        // About Section
        $aboutPage = new Page();
        $aboutPage->setName('About Us');
        $aboutPage->setSlug('about');
        $aboutPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $aboutPage->setTemplate('default');
        $aboutPage->getSEO()->title = 'About Adeliom - Expert Symfony Development & Bundle Solutions';
        $aboutPage->getSEO()->description = 'Learn about Adeliom\'s expertise in Symfony development, our comprehensive bundle ecosystem, and how we help businesses build powerful web applications with cutting-edge technology.';
        $aboutPage->getSEO()->keywords = 'Adeliom about, Symfony experts, web development agency, PHP development, bundle development, software solutions, digital transformation';
        $aboutPage->getSEO()->cannonical = 'https://example.com/about';
        $aboutPage->getSEO()->sitemap = true;
        $aboutPage->getSEO()->robots = ['index', 'follow'];
        $aboutPage->setContent([
            [
                'text' => 'Welcome to Adeliom EasyAdmin Sandbox',
                'position' => '0',
                'block_type' => HeaderType::class
            ],
        ]);
        $manager->persist($aboutPage);

        $aboutCompanyPage = new Page();
        $aboutCompanyPage->setName('Company History');
        $aboutCompanyPage->setSlug('company-history');
        $aboutCompanyPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $aboutCompanyPage->setParent($aboutPage);
        $aboutCompanyPage->setTemplate('default');
        $aboutCompanyPage->getSEO()->title = 'Company History - Adeliom\'s Journey in Symfony Development';
        $aboutCompanyPage->getSEO()->description = 'Discover Adeliom\'s evolution from startup to Symfony development leader. Learn about our milestones, achievements, and commitment to building exceptional web solutions since our founding.';
        $aboutCompanyPage->getSEO()->keywords = 'Adeliom history, company timeline, Symfony development journey, web development milestones, PHP framework expertise, business evolution';
        $aboutCompanyPage->getSEO()->cannonical = 'https://example.com/about/company-history';
        $aboutCompanyPage->getSEO()->sitemap = true;
        $aboutCompanyPage->getSEO()->robots = ['index', 'follow'];
        $manager->persist($aboutCompanyPage);

        $aboutTeamPage = new Page();
        $aboutTeamPage->setName('Our Team');
        $aboutTeamPage->setSlug('our-team');
        $aboutTeamPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $aboutTeamPage->setParent($aboutPage);
        $aboutTeamPage->setTemplate('default');
        $aboutTeamPage->getSEO()->title = 'Our Expert Development Team - Adeliom Symfony Specialists';
        $aboutTeamPage->getSEO()->description = 'Meet the talented developers, designers, and consultants behind Adeliom. Our experienced team specializes in Symfony development, bundle creation, and innovative web solutions.';
        $aboutTeamPage->getSEO()->keywords = 'Adeliom team, Symfony developers, PHP experts, web development team, software engineers, development specialists, technical consultants';
        $aboutTeamPage->getSEO()->cannonical = 'https://example.com/about/our-team';
        $aboutTeamPage->getSEO()->sitemap = true;
        $aboutTeamPage->getSEO()->robots = ['index', 'follow'];
        $manager->persist($aboutTeamPage);

        $aboutCareersPage = new Page();
        $aboutCareersPage->setName('Careers');
        $aboutCareersPage->setSlug('careers');
        $aboutCareersPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $aboutCareersPage->setParent($aboutPage);
        $aboutCareersPage->setTemplate('default');
        $aboutCareersPage->getSEO()->title = 'Join Adeliom - Symfony Development Career Opportunities';
        $aboutCareersPage->getSEO()->description = 'Explore exciting career opportunities at Adeliom. Join our team of Symfony experts and work on cutting-edge web development projects with innovative technologies and supportive culture.';
        $aboutCareersPage->getSEO()->keywords = 'Adeliom careers, Symfony jobs, PHP developer jobs, web development careers, software engineer positions, remote work opportunities';
        $aboutCareersPage->getSEO()->cannonical = 'https://example.com/about/careers';
        $aboutCareersPage->getSEO()->sitemap = true;
        $aboutCareersPage->getSEO()->robots = ['index', 'follow'];
        $manager->persist($aboutCareersPage);

        // Services Section
        $servicesPage = new Page();
        $servicesPage->setName('Services');
        $servicesPage->setSlug('services');
        $servicesPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $servicesPage->setTemplate('default');
        $servicesPage->getSEO()->title = 'Professional Web Development Services - Adeliom Symfony Experts';
        $servicesPage->getSEO()->description = 'Comprehensive web development services including Symfony applications, custom bundles, mobile development, and technical consulting. Expert solutions for modern businesses.';
        $servicesPage->getSEO()->keywords = 'web development services, Symfony development, PHP applications, custom bundle development, mobile apps, technical consulting, software solutions';
        $servicesPage->getSEO()->cannonical = 'https://example.com/services';
        $servicesPage->getSEO()->sitemap = true;
        $servicesPage->getSEO()->robots = ['index', 'follow'];
        $manager->persist($servicesPage);

        $webDevPage = new Page();
        $webDevPage->setName('Web Development');
        $webDevPage->setSlug('web-development');
        $webDevPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $webDevPage->setParent($servicesPage);
        $webDevPage->setTemplate('default');
        $webDevPage->getSEO()->title = 'Expert Web Development Services - Modern PHP & Symfony Solutions';
        $webDevPage->getSEO()->description = 'Professional web development using cutting-edge technologies. Specializing in Symfony, React, and modern PHP frameworks for scalable, secure web applications.';
        $webDevPage->getSEO()->keywords = 'web development, PHP development, Symfony web apps, modern web applications, responsive design, full-stack development';
        $webDevPage->getSEO()->cannonical = 'https://example.com/services/web-development';
        $webDevPage->getSEO()->sitemap = true;
        $webDevPage->getSEO()->robots = ['index', 'follow'];
        $manager->persist($webDevPage);

        $symfonyDevPage = new Page();
        $symfonyDevPage->setName('Symfony Development');
        $symfonyDevPage->setSlug('symfony-development');
        $symfonyDevPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $symfonyDevPage->setParent($webDevPage);
        $symfonyDevPage->setTemplate('default');
        $symfonyDevPage->getSEO()->title = 'Symfony Framework Development - Expert PHP Web Applications';
        $symfonyDevPage->getSEO()->description = 'Professional Symfony development services for enterprise web applications. Custom bundle creation, API development, and scalable solutions using the Symfony framework.';
        $symfonyDevPage->getSEO()->keywords = 'Symfony development, PHP framework, Symfony applications, custom bundles, API development, enterprise web solutions, MVC architecture';
        $symfonyDevPage->getSEO()->cannonical = 'https://example.com/services/web-development/symfony-development';
        $symfonyDevPage->getSEO()->sitemap = true;
        $symfonyDevPage->getSEO()->robots = ['index', 'follow'];
        $manager->persist($symfonyDevPage);

        $reactDevPage = new Page();
        $reactDevPage->setName('React Development');
        $reactDevPage->setSlug('react-development');
        $reactDevPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $reactDevPage->setParent($webDevPage);
        $reactDevPage->setTemplate('default');
        $reactDevPage->getSEO()->title = 'React Development Services - Modern JavaScript Applications';
        $reactDevPage->getSEO()->description = 'Expert React development for interactive web applications. Create dynamic user interfaces, single-page applications, and modern frontend solutions with React.js.';
        $reactDevPage->getSEO()->keywords = 'React development, JavaScript applications, React.js, frontend development, single-page applications, interactive web interfaces, modern UI';
        $reactDevPage->getSEO()->cannonical = 'https://example.com/services/web-development/react-development';
        $reactDevPage->getSEO()->sitemap = true;
        $reactDevPage->getSEO()->robots = ['index', 'follow'];
        $manager->persist($reactDevPage);

        $mobileDevPage = new Page();
        $mobileDevPage->setName('Mobile Development');
        $mobileDevPage->setSlug('mobile-development');
        $mobileDevPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $mobileDevPage->setParent($servicesPage);
        $mobileDevPage->setTemplate('default');
        $mobileDevPage->getSEO()->title = 'Mobile App Development - iOS & Android Applications';
        $mobileDevPage->getSEO()->description = 'Professional mobile application development for iOS and Android platforms. Native and cross-platform solutions for businesses and startups.';
        $mobileDevPage->getSEO()->keywords = 'mobile app development, iOS development, Android development, mobile applications, cross-platform apps, native mobile apps';
        $mobileDevPage->getSEO()->cannonical = 'https://example.com/services/mobile-development';
        $mobileDevPage->getSEO()->sitemap = true;
        $mobileDevPage->getSEO()->robots = ['index', 'follow'];
        $manager->persist($mobileDevPage);

        $iosDevPage = new Page();
        $iosDevPage->setName('iOS Development');
        $iosDevPage->setSlug('ios-development');
        $iosDevPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $iosDevPage->setParent($mobileDevPage);
        $iosDevPage->setTemplate('default');
        $iosDevPage->getSEO()->title = 'iOS App Development - Professional iPhone & iPad Applications';
        $iosDevPage->getSEO()->description = 'Expert iOS development services for iPhone and iPad applications. Native iOS apps using Swift and Objective-C with App Store optimization.';
        $iosDevPage->getSEO()->keywords = 'iOS development, iPhone apps, iPad applications, Swift development, iOS native apps, App Store, mobile iOS solutions';
        $iosDevPage->getSEO()->cannonical = 'https://example.com/services/mobile-development/ios-development';
        $iosDevPage->getSEO()->sitemap = true;
        $iosDevPage->getSEO()->robots = ['index', 'follow'];
        $manager->persist($iosDevPage);

        $androidDevPage = new Page();
        $androidDevPage->setName('Android Development');
        $androidDevPage->setSlug('android-development');
        $androidDevPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $androidDevPage->setParent($mobileDevPage);
        $androidDevPage->setTemplate('default');
        $androidDevPage->getSEO()->title = 'Android App Development - Professional Mobile Applications';
        $androidDevPage->getSEO()->description = 'Professional Android development services for smartphones and tablets. Native Android apps using Java and Kotlin with Google Play Store optimization.';
        $androidDevPage->getSEO()->keywords = 'Android development, Android apps, Java development, Kotlin development, Google Play Store, mobile Android solutions, smartphone apps';
        $androidDevPage->getSEO()->cannonical = 'https://example.com/services/mobile-development/android-development';
        $androidDevPage->getSEO()->sitemap = true;
        $androidDevPage->getSEO()->robots = ['index', 'follow'];
        $manager->persist($androidDevPage);

        $consultingPage = new Page();
        $consultingPage->setName('Consulting');
        $consultingPage->setSlug('consulting');
        $consultingPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $consultingPage->setParent($servicesPage);
        $consultingPage->setTemplate('default');
        $consultingPage->getSEO()->title = 'Technical Consulting Services - Web Development & Architecture';
        $consultingPage->getSEO()->description = 'Expert technical consulting for web development projects. Architecture planning, technology selection, code reviews, and strategic development guidance.';
        $consultingPage->getSEO()->keywords = 'technical consulting, web development consulting, software architecture, technology consulting, code review, development strategy';
        $consultingPage->getSEO()->cannonical = 'https://example.com/services/consulting';
        $consultingPage->getSEO()->sitemap = true;
        $consultingPage->getSEO()->robots = ['index', 'follow'];
        $manager->persist($consultingPage);

        $techConsultingPage = new Page();
        $techConsultingPage->setName('Technical Consulting');
        $techConsultingPage->setSlug('technical-consulting');
        $techConsultingPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $techConsultingPage->setParent($consultingPage);
        $techConsultingPage->setTemplate('default');
        $techConsultingPage->getSEO()->title = 'Technical Consulting - Software Architecture & Development Expertise';
        $techConsultingPage->getSEO()->description = 'Specialized technical consulting for complex software projects. Architecture design, performance optimization, security audits, and technical team guidance.';
        $techConsultingPage->getSEO()->keywords = 'technical consulting, software architecture, performance optimization, security audit, technical guidance, code optimization, system design';
        $techConsultingPage->getSEO()->cannonical = 'https://example.com/services/consulting/technical-consulting';
        $techConsultingPage->getSEO()->sitemap = true;
        $techConsultingPage->getSEO()->robots = ['index', 'follow'];
        $manager->persist($techConsultingPage);

        $strategyConsultingPage = new Page();
        $strategyConsultingPage->setName('Strategy Consulting');
        $strategyConsultingPage->setSlug('strategy-consulting');
        $strategyConsultingPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $strategyConsultingPage->setParent($consultingPage);
        $strategyConsultingPage->setTemplate('default');
        $strategyConsultingPage->getSEO()->title = 'Digital Strategy Consulting - Technology & Business Alignment';
        $strategyConsultingPage->getSEO()->description = 'Strategic consulting for digital transformation and technology adoption. Business-technology alignment, roadmap planning, and digital innovation strategies.';
        $strategyConsultingPage->getSEO()->keywords = 'digital strategy, technology strategy, digital transformation, business consulting, innovation strategy, technology roadmap, strategic planning';
        $strategyConsultingPage->getSEO()->cannonical = 'https://example.com/services/consulting/strategy-consulting';
        $strategyConsultingPage->getSEO()->sitemap = true;
        $strategyConsultingPage->getSEO()->robots = ['index', 'follow'];
        $manager->persist($strategyConsultingPage);

        // Portfolio Section
        $portfolioPage = new Page();
        $portfolioPage->setName('Portfolio');
        $portfolioPage->setSlug('portfolio');
        $portfolioPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $portfolioPage->setTemplate('default');
        $portfolioPage->getSEO()->title = 'Development Portfolio - Adeliom Project Showcase';
        $portfolioPage->getSEO()->description = 'Explore Adeliom\'s portfolio of successful web development projects, mobile applications, and Symfony solutions. See our expertise in action through real client work.';
        $portfolioPage->getSEO()->keywords = 'development portfolio, web projects, Symfony projects, mobile apps, client work, project showcase, development examples';
        $portfolioPage->getSEO()->cannonical = 'https://example.com/portfolio';
        $portfolioPage->getSEO()->sitemap = true;
        $portfolioPage->getSEO()->robots = ['index', 'follow'];
        $manager->persist($portfolioPage);

        $portfolioWebPage = new Page();
        $portfolioWebPage->setName('Web Projects');
        $portfolioWebPage->setSlug('web-projects');
        $portfolioWebPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $portfolioWebPage->setParent($portfolioPage);
        $portfolioWebPage->setTemplate('default');
        $portfolioWebPage->getSEO()->title = 'Web Development Portfolio - Symfony & PHP Project Examples';
        $portfolioWebPage->getSEO()->description = 'Browse our web development portfolio featuring Symfony applications, e-commerce platforms, CMS solutions, and custom web applications built for diverse clients.';
        $portfolioWebPage->getSEO()->keywords = 'web development portfolio, Symfony projects, PHP web applications, e-commerce sites, CMS development, custom web solutions';
        $portfolioWebPage->getSEO()->cannonical = 'https://example.com/portfolio/web-projects';
        $portfolioWebPage->getSEO()->sitemap = true;
        $portfolioWebPage->getSEO()->robots = ['index', 'follow'];
        $manager->persist($portfolioWebPage);

        $ecommerceProjectPage = new Page();
        $ecommerceProjectPage->setName('E-commerce Platform');
        $ecommerceProjectPage->setSlug('ecommerce-platform');
        $ecommerceProjectPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $ecommerceProjectPage->setParent($portfolioWebPage);
        $ecommerceProjectPage->setTemplate('default');
        $ecommerceProjectPage->getSEO()->title = 'E-commerce Platform Case Study - Symfony Online Store Solution';
        $ecommerceProjectPage->getSEO()->description = 'Detailed case study of our custom e-commerce platform built with Symfony. Features payment integration, inventory management, and scalable architecture for online retail.';
        $ecommerceProjectPage->getSEO()->keywords = 'e-commerce platform, Symfony online store, custom shopping cart, payment integration, inventory management, online retail solution';
        $ecommerceProjectPage->getSEO()->cannonical = 'https://example.com/portfolio/web-projects/ecommerce-platform';
        $ecommerceProjectPage->getSEO()->sitemap = true;
        $ecommerceProjectPage->getSEO()->robots = ['index', 'follow'];
        $manager->persist($ecommerceProjectPage);

        $cmsProjectPage = new Page();
        $cmsProjectPage->setName('CMS Solution');
        $cmsProjectPage->setSlug('cms-solution');
        $cmsProjectPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $cmsProjectPage->setParent($portfolioWebPage);
        $cmsProjectPage->setTemplate('default');
        $cmsProjectPage->getSEO()->title = 'Custom CMS Solution - Content Management System Case Study';
        $cmsProjectPage->getSEO()->description = 'Learn about our custom CMS solution built with Symfony and EasyAdmin. Features content editing, media management, user permissions, and multi-language support.';
        $cmsProjectPage->getSEO()->keywords = 'custom CMS, content management system, Symfony CMS, EasyAdmin, content editing, media management, multi-language CMS';
        $cmsProjectPage->getSEO()->cannonical = 'https://example.com/portfolio/web-projects/cms-solution';
        $cmsProjectPage->getSEO()->sitemap = true;
        $cmsProjectPage->getSEO()->robots = ['index', 'follow'];
        $manager->persist($cmsProjectPage);

        $portfolioMobilePage = new Page();
        $portfolioMobilePage->setName('Mobile Apps');
        $portfolioMobilePage->setSlug('mobile-apps');
        $portfolioMobilePage->setState(ThreeStateStatusEnum::PUBLISHED);
        $portfolioMobilePage->setParent($portfolioPage);
        $portfolioMobilePage->setTemplate('default');
        $portfolioMobilePage->getSEO()->title = 'Mobile App Portfolio - iOS & Android Application Examples';
        $portfolioMobilePage->getSEO()->description = 'Discover our mobile application portfolio featuring iOS and Android apps. See examples of native mobile development, cross-platform solutions, and app store successes.';
        $portfolioMobilePage->getSEO()->keywords = 'mobile app portfolio, iOS apps, Android applications, native mobile development, cross-platform apps, mobile solutions';
        $portfolioMobilePage->getSEO()->cannonical = 'https://example.com/portfolio/mobile-apps';
        $portfolioMobilePage->getSEO()->sitemap = true;
        $portfolioMobilePage->getSEO()->robots = ['index', 'follow'];
        $manager->persist($portfolioMobilePage);

        // Support Section
        $supportPage = new Page();
        $supportPage->setName('Support');
        $supportPage->setSlug('support');
        $supportPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $supportPage->setTemplate('default');
        $supportPage->getSEO()->title = 'Customer Support - Adeliom Technical Assistance & Resources';
        $supportPage->getSEO()->description = 'Get professional support for Adeliom\'s Symfony bundles and web development services. Access documentation, tutorials, FAQ, and direct technical assistance from our expert team.';
        $supportPage->getSEO()->keywords = 'Adeliom support, technical assistance, Symfony bundle support, customer service, documentation, tutorials, help center, technical support';
        $supportPage->getSEO()->cannonical = 'https://example.com/support';
        $supportPage->getSEO()->sitemap = true;
        $supportPage->getSEO()->robots = ['index', 'follow'];
        $manager->persist($supportPage);

        $documentationPage = new Page();
        $documentationPage->setName('Documentation');
        $documentationPage->setSlug('documentation');
        $documentationPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $documentationPage->setParent($supportPage);
        $documentationPage->setTemplate('default');
        $documentationPage->getSEO()->title = 'Documentation - Comprehensive Symfony Bundle Guides';
        $documentationPage->getSEO()->description = 'Access comprehensive documentation for all Adeliom Symfony bundles. Find installation guides, configuration examples, API references, and best practices for web development.';
        $documentationPage->getSEO()->keywords = 'Symfony documentation, bundle guides, API reference, installation guide, configuration, development docs, programming guides';
        $documentationPage->getSEO()->cannonical = 'https://example.com/support/documentation';
        $documentationPage->getSEO()->sitemap = true;
        $documentationPage->getSEO()->robots = ['index', 'follow'];
        $manager->persist($documentationPage);

        $apiDocsPage = new Page();
        $apiDocsPage->setName('API Documentation');
        $apiDocsPage->setSlug('api-documentation');
        $apiDocsPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $apiDocsPage->setParent($documentationPage);
        $apiDocsPage->setTemplate('default');
        $apiDocsPage->getSEO()->title = 'API Documentation - Symfony Bundle REST API Reference';
        $apiDocsPage->getSEO()->description = 'Complete API documentation for Adeliom Symfony bundles. Explore REST endpoints, request/response examples, authentication methods, and integration guides for developers.';
        $apiDocsPage->getSEO()->keywords = 'API documentation, REST API, Symfony API, endpoint reference, API integration, developer documentation, web services, API guide';
        $apiDocsPage->getSEO()->cannonical = 'https://example.com/support/documentation/api-documentation';
        $apiDocsPage->getSEO()->sitemap = true;
        $apiDocsPage->getSEO()->robots = ['index', 'follow'];
        $manager->persist($apiDocsPage);

        $userGuidePage = new Page();
        $userGuidePage->setName('User Guide');
        $userGuidePage->setSlug('user-guide');
        $userGuidePage->setState(ThreeStateStatusEnum::PUBLISHED);
        $userGuidePage->setParent($documentationPage);
        $userGuidePage->setTemplate('default');
        $userGuidePage->getSEO()->title = 'User Guide - Step-by-Step Symfony Bundle Tutorial';
        $userGuidePage->getSEO()->description = 'Learn how to use Adeliom Symfony bundles with our comprehensive user guide. Step-by-step tutorials, common use cases, troubleshooting, and best practices for beginners and experts.';
        $userGuidePage->getSEO()->keywords = 'user guide, Symfony tutorial, bundle tutorial, step-by-step guide, how-to guide, user manual, beginner guide, troubleshooting';
        $userGuidePage->getSEO()->cannonical = 'https://example.com/support/documentation/user-guide';
        $userGuidePage->getSEO()->sitemap = true;
        $userGuidePage->getSEO()->robots = ['index', 'follow'];
        $manager->persist($userGuidePage);

        $contactPage = new Page();
        $contactPage->setName('Contact');
        $contactPage->setSlug('contact');
        $contactPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $contactPage->setTemplate('contact');
        $contactPage->getSEO()->title = 'Contact Adeliom - Get in Touch for Symfony Development Services';
        $contactPage->getSEO()->description = 'Contact Adeliom for expert Symfony development services, custom bundle solutions, and web application development. Reach out to our team for professional consultation and support.';
        $contactPage->getSEO()->keywords = 'contact Adeliom, Symfony development services, web development consultation, PHP development support, custom bundle development, software development contact';
        $contactPage->getSEO()->cannonical = 'https://example.com/contact';
        $contactPage->getSEO()->sitemap = true;
        $contactPage->getSEO()->robots = ['index', 'follow'];
        $manager->persist($contactPage);

        // Configuration display page
        $configPage = new Page();
        $configPage->setName('System Configuration');
        $configPage->setSlug('system-config');
        $configPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $configPage->setTemplate('config');
        $configPage->getSEO()->title = 'System Configuration - EasyConfig Bundle Demo';
        $configPage->getSEO()->description = 'Explore the EasyConfig bundle capabilities with this live demonstration of database-driven configuration management. View all system settings and configuration options.';
        $configPage->getSEO()->keywords = 'EasyConfig bundle, system configuration, database configuration, Symfony configuration, dynamic settings, configuration management';
        $configPage->getSEO()->cannonical = 'https://example.com/system-config';
        $configPage->getSEO()->sitemap = true;
        $configPage->getSEO()->robots = ['index', 'follow'];
        $manager->persist($configPage);

        // Unpublished page for testing
        $draftPage = new Page();
        $draftPage->setName('Coming Soon');
        $draftPage->setSlug('coming-soon');
        $draftPage->setState(ThreeStateStatusEnum::UNPUBLISHED);
        $draftPage->setTemplate('default');
        $draftPage->getSEO()->title = 'Coming Soon - New Features in Development';
        $draftPage->getSEO()->description = 'Exciting new features are coming soon to the Adeliom EasyAdmin Sandbox. Stay tuned for updates and enhancements to our Symfony bundle ecosystem.';
        $draftPage->getSEO()->keywords = 'coming soon, new features, development, updates, Adeliom roadmap, future releases';
        $draftPage->getSEO()->cannonical = 'https://example.com/coming-soon';
        $draftPage->getSEO()->sitemap = false;
        $draftPage->getSEO()->robots = ['noindex', 'nofollow'];
        $manager->persist($draftPage);

        // Under Construction page for redirects
        $underConstructionPage = new Page();
        $underConstructionPage->setName('Under Construction');
        $underConstructionPage->setSlug('under-construction');
        $underConstructionPage->setState(ThreeStateStatusEnum::PUBLISHED);
        $underConstructionPage->setTemplate('default');
        $underConstructionPage->getSEO()->title = 'Page Under Construction - Adeliom Development in Progress';
        $underConstructionPage->getSEO()->description = 'This page is currently under construction as we work on new features and improvements. Please check back soon for updates or explore our other available resources.';
        $underConstructionPage->getSEO()->keywords = 'under construction, development in progress, coming soon, website maintenance, temporary page';
        $underConstructionPage->getSEO()->cannonical = 'https://example.com/under-construction';
        $underConstructionPage->getSEO()->sitemap = false;
        $underConstructionPage->getSEO()->robots = ['noindex', 'follow'];
        $manager->persist($underConstructionPage);

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
