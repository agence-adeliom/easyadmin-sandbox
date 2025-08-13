<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\EasyRedirect\Redirect;
use App\Entity\EasyRedirect\NotFound;

class RedirectFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Permanent redirects (301)
        $redirect1 = new Redirect('/old-homepage', '/', 'easyadmin-sandbox.ddev.site', 301);
        $redirect1->increaseCount(15);
        $redirect1->updateLastAccessed(new \DateTime('-2 days'));
        $manager->persist($redirect1);

        $redirect2 = new Redirect('/old-about', '/about', 'easyadmin-sandbox.ddev.site', 301);
        $redirect2->increaseCount(8);
        $redirect2->updateLastAccessed(new \DateTime('-1 week'));
        $manager->persist($redirect2);

        $redirect3 = new Redirect('/legacy-blog', '/blog', 'easyadmin-sandbox.ddev.site', 301);
        $redirect3->increaseCount(23);
        $redirect3->updateLastAccessed(new \DateTime('-3 hours'));
        $manager->persist($redirect3);

        $redirect4 = new Redirect('/old-contact-us', '/contact', 'easyadmin-sandbox.ddev.site', 301);
        $redirect4->increaseCount(5);
        $redirect4->updateLastAccessed(new \DateTime('-5 days'));
        $manager->persist($redirect4);

        // Temporary redirects (302)
        $redirect5 = new Redirect('/maintenance', '/under-construction', 'easyadmin-sandbox.ddev.site', 302);
        $redirect5->increaseCount(45);
        $redirect5->updateLastAccessed(new \DateTime('-30 minutes'));
        $manager->persist($redirect5);

        // External redirect
        $redirect6 = new Redirect('/github', 'https://github.com/agence-adeliom', 'easyadmin-sandbox.ddev.site', 301);
        $redirect6->increaseCount(12);
        $redirect6->updateLastAccessed(new \DateTime('-2 hours'));
        $manager->persist($redirect6);

        // Blog post redirects
        $redirect7 = new Redirect('/blog/old-post-slug', '/blog/technology/future-of-web-development', 'easyadmin-sandbox.ddev.site', 301);
        $redirect7->increaseCount(7);
        $redirect7->updateLastAccessed(new \DateTime('-1 day'));
        $manager->persist($redirect7);

        $redirect8 = new Redirect('/articles/symfony-tips', '/blog/technology/getting-started-symfony-7', 'easyadmin-sandbox.ddev.site', 301);
        $redirect8->increaseCount(19);
        $redirect8->updateLastAccessed(new \DateTime('-6 hours'));
        $manager->persist($redirect8);

        // Category redirects
        $redirect9 = new Redirect('/category/tech', '/blog/technology', 'easyadmin-sandbox.ddev.site', 301);
        $redirect9->increaseCount(34);
        $redirect9->updateLastAccessed(new \DateTime('-4 hours'));
        $manager->persist($redirect9);

        $redirect10 = new Redirect('/faq/old-category', '/faq/general-questions', 'easyadmin-sandbox.ddev.site', 301);
        $redirect10->increaseCount(6);
        $redirect10->updateLastAccessed(new \DateTime('-12 hours'));
        $manager->persist($redirect10);

        // Sample NotFound entries (404 errors that were recorded)
        $notFound1 = new NotFound('/missing-page', 'https://example.com/missing-page', 'https://example.com/', new \DateTime('-1 week'));
        $manager->persist($notFound1);

        $notFound2 = new NotFound('/broken-link', 'https://example.com/broken-link', 'https://google.com/', new \DateTime('-3 days'));
        $manager->persist($notFound2);

        $notFound3 = new NotFound('/old-resource.pdf', 'https://example.com/old-resource.pdf', 'https://example.com/blog', new \DateTime('-2 days'));
        $manager->persist($notFound3);

        $notFound4 = new NotFound('/api/v1/old-endpoint', 'https://example.com/api/v1/old-endpoint', null, new \DateTime('-5 hours'));
        $manager->persist($notFound4);

        $notFound5 = new NotFound('/wp-admin', 'https://example.com/wp-admin', null, new \DateTime('-30 minutes'));
        $manager->persist($notFound5);

        $manager->flush();
    }
}
