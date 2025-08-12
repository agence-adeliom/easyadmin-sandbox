<?php

namespace App\DataFixtures;

use Adeliom\EasyCommonBundle\Enum\ThreeStateStatusEnum;
use App\Entity\EasyMenu\Menu;
use App\Entity\EasyMenu\MenuItem;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EasyMenuFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Create Main Navigation Menu
        $mainMenu = new Menu();
        $mainMenu->setName('Main Navigation');
        $mainMenu->setCode('main');
        $mainMenu->setStatus(true);
        $manager->persist($mainMenu);

        // Create Footer Menu
        $footerMenu = new Menu();
        $footerMenu->setName('Footer Links');
        $footerMenu->setCode('footer');
        $footerMenu->setStatus(true);
        $manager->persist($footerMenu);

        // Create Main Menu Items
        $homeItem = new MenuItem();
        $homeItem->setName('Home');
        $homeItem->setUrl('/');
        $homeItem->setState(ThreeStateStatusEnum::PUBLISHED);
        $homeItem->setPublishDate(new \DateTime('-1 month'));
        $homeItem->setMenu($mainMenu);
        $manager->persist($homeItem);

        $aboutItem = new MenuItem();
        $aboutItem->setName('About Us');
        $aboutItem->setUrl('/about');
        $aboutItem->setState(ThreeStateStatusEnum::PUBLISHED);
        $aboutItem->setPublishDate(new \DateTime('-1 month'));
        $aboutItem->setMenu($mainMenu);
        $manager->persist($aboutItem);

        $servicesItem = new MenuItem();
        $servicesItem->setName('Services');
        $servicesItem->setUrl('/services');
        $servicesItem->setState(ThreeStateStatusEnum::PUBLISHED);
        $servicesItem->setPublishDate(new \DateTime('-1 month'));
        $servicesItem->setMenu($mainMenu);
        $manager->persist($servicesItem);

        // Services Sub-menu Items
        $webDevItem = new MenuItem();
        $webDevItem->setName('Web Development');
        $webDevItem->setUrl('/services/web-development');
        $webDevItem->setState(ThreeStateStatusEnum::PUBLISHED);
        $webDevItem->setPublishDate(new \DateTime('-3 weeks'));
        $webDevItem->setMenu($mainMenu);
        $webDevItem->setParent($servicesItem);
        $manager->persist($webDevItem);

        $mobileDevItem = new MenuItem();
        $mobileDevItem->setName('Mobile Development');
        $mobileDevItem->setUrl('/services/mobile-development');
        $mobileDevItem->setState(ThreeStateStatusEnum::PUBLISHED);
        $mobileDevItem->setPublishDate(new \DateTime('-3 weeks'));
        $mobileDevItem->setMenu($mainMenu);
        $mobileDevItem->setParent($servicesItem);
        $manager->persist($mobileDevItem);

        $consultingItem = new MenuItem();
        $consultingItem->setName('Consulting');
        $consultingItem->setUrl('/services/consulting');
        $consultingItem->setState(ThreeStateStatusEnum::PUBLISHED);
        $consultingItem->setPublishDate(new \DateTime('-3 weeks'));
        $consultingItem->setMenu($mainMenu);
        $consultingItem->setParent($servicesItem);
        $manager->persist($consultingItem);

        $blogItem = new MenuItem();
        $blogItem->setName('Blog');
        $blogItem->setUrl('/blog');
        $blogItem->setState(ThreeStateStatusEnum::PUBLISHED);
        $blogItem->setPublishDate(new \DateTime('-1 month'));
        $blogItem->setMenu($mainMenu);
        $manager->persist($blogItem);

        $faqItem = new MenuItem();
        $faqItem->setName('FAQ');
        $faqItem->setUrl('/faq');
        $faqItem->setState(ThreeStateStatusEnum::PUBLISHED);
        $faqItem->setPublishDate(new \DateTime('-1 month'));
        $faqItem->setMenu($mainMenu);
        $manager->persist($faqItem);

        $contactItem = new MenuItem();
        $contactItem->setName('Contact');
        $contactItem->setUrl('/contact');
        $contactItem->setState(ThreeStateStatusEnum::PUBLISHED);
        $contactItem->setPublishDate(new \DateTime('-1 month'));
        $contactItem->setMenu($mainMenu);
        $manager->persist($contactItem);

        // Footer Menu Items
        $privacyItem = new MenuItem();
        $privacyItem->setName('Privacy Policy');
        $privacyItem->setUrl('/privacy');
        $privacyItem->setState(ThreeStateStatusEnum::PUBLISHED);
        $privacyItem->setPublishDate(new \DateTime('-1 month'));
        $privacyItem->setMenu($footerMenu);
        $manager->persist($privacyItem);

        $termsItem = new MenuItem();
        $termsItem->setName('Terms of Service');
        $termsItem->setUrl('/terms');
        $termsItem->setState(ThreeStateStatusEnum::PUBLISHED);
        $termsItem->setPublishDate(new \DateTime('-1 month'));
        $termsItem->setMenu($footerMenu);
        $manager->persist($termsItem);

        $cookiesItem = new MenuItem();
        $cookiesItem->setName('Cookie Policy');
        $cookiesItem->setUrl('/cookies');
        $cookiesItem->setState(ThreeStateStatusEnum::PUBLISHED);
        $cookiesItem->setPublishDate(new \DateTime('-1 month'));
        $cookiesItem->setMenu($footerMenu);
        $manager->persist($cookiesItem);

        $sitemapItem = new MenuItem();
        $sitemapItem->setName('Sitemap');
        $sitemapItem->setUrl('/sitemap');
        $sitemapItem->setState(ThreeStateStatusEnum::PUBLISHED);
        $sitemapItem->setPublishDate(new \DateTime('-1 month'));
        $sitemapItem->setMenu($footerMenu);
        $manager->persist($sitemapItem);

        // External link example
        $githubItem = new MenuItem();
        $githubItem->setName('GitHub');
        $githubItem->setUrl('https://github.com/agence-adeliom');
        $githubItem->setState(ThreeStateStatusEnum::PUBLISHED);
        $githubItem->setPublishDate(new \DateTime('-2 weeks'));
        $githubItem->setMenu($footerMenu);
        $manager->persist($githubItem);

        // Draft menu item (not published)
        $draftItem = new MenuItem();
        $draftItem->setName('Coming Soon');
        $draftItem->setUrl('/coming-soon');
        $draftItem->setState(ThreeStateStatusEnum::UNPUBLISHED);
        $draftItem->setMenu($mainMenu);
        $manager->persist($draftItem);

        $manager->flush();
    }
}