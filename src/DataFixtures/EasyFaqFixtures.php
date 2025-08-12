<?php

namespace App\DataFixtures;

use Adeliom\EasyCommonBundle\Enum\ThreeStateStatusEnum;
use App\Entity\EasyFaq\Category;
use App\Entity\EasyFaq\Entry;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EasyFaqFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Create FAQ Categories
        $generalCategory = new Category();
        $generalCategory->setName('General Questions');
        $generalCategory->setSlug('general-questions');
        $generalCategory->setStatus(true);
        $generalCategory->getSEO()->title = 'General Questions - FAQ';
        $generalCategory->getSEO()->description = 'Frequently asked general questions about our services';
        $manager->persist($generalCategory);

        $technicalCategory = new Category();
        $technicalCategory->setName('Technical Support');
        $technicalCategory->setSlug('technical-support');
        $technicalCategory->setStatus(true);
        $technicalCategory->getSEO()->title = 'Technical Support - FAQ';
        $technicalCategory->getSEO()->description = 'Technical questions and troubleshooting help';
        $manager->persist($technicalCategory);

        $billingCategory = new Category();
        $billingCategory->setName('Billing & Pricing');
        $billingCategory->setSlug('billing-pricing');
        $billingCategory->setStatus(true);
        $billingCategory->getSEO()->title = 'Billing & Pricing - FAQ';
        $billingCategory->getSEO()->description = 'Questions about billing, payments, and pricing plans';
        $manager->persist($billingCategory);

        $accountCategory = new Category();
        $accountCategory->setName('Account Management');
        $accountCategory->setSlug('account-management');
        $accountCategory->setStatus(true);
        $accountCategory->getSEO()->title = 'Account Management - FAQ';
        $accountCategory->getSEO()->description = 'Managing your account settings and preferences';
        $manager->persist($accountCategory);

        // Create FAQ Entries - General Questions
        $entry1 = new Entry();
        $entry1->setName('What is Adeliom EasyAdmin Sandbox?');
        $entry1->setSlug('what-is-easyadmin-sandbox');
        $entry1->setCategory($generalCategory);
        $entry1->setState(ThreeStateStatusEnum::PUBLISHED);
        $entry1->setPublishDate(new \DateTime('-1 month'));
        $entry1->setAnswer('The Adeliom EasyAdmin Sandbox is a comprehensive demonstration platform showcasing our Easy* bundle ecosystem for Symfony applications. It provides developers with a hands-on environment to explore and test various bundles including EasyBlog, EasyMedia, EasyFAQ, and more.');
        $entry1->getSEO()->title = 'What is Adeliom EasyAdmin Sandbox?';
        $entry1->getSEO()->description = 'Learn about the EasyAdmin Sandbox and its features';
        $manager->persist($entry1);

        $entry2 = new Entry();
        $entry2->setName('How do I get started?');
        $entry2->setSlug('how-to-get-started');
        $entry2->setCategory($generalCategory);
        $entry2->setState(ThreeStateStatusEnum::PUBLISHED);
        $entry2->setPublishDate(new \DateTime('-3 weeks'));
        $entry2->setAnswer('Getting started is easy! First, clone the repository from GitHub. Then run `ddev start` to set up your local development environment, followed by `ddev composer install` to install dependencies. Finally, run the migrations and load fixtures with `ddev console doctrine:migrations:migrate` and `ddev console doctrine:fixtures:load`.');
        $entry2->getSEO()->title = 'How to get started with EasyAdmin Sandbox';
        $entry2->getSEO()->description = 'Step-by-step guide to setting up the sandbox environment';
        $manager->persist($entry2);

        $entry3 = new Entry();
        $entry3->setName('Is this free to use?');
        $entry3->setSlug('is-this-free');
        $entry3->setCategory($generalCategory);
        $entry3->setState(ThreeStateStatusEnum::PUBLISHED);
        $entry3->setPublishDate(new \DateTime('-2 weeks'));
        $entry3->setAnswer('Yes, the EasyAdmin Sandbox is completely free to use for development and testing purposes. It\'s designed to help developers evaluate our bundle ecosystem before implementing it in production projects.');
        $entry3->getSEO()->title = 'Is EasyAdmin Sandbox free to use?';
        $entry3->getSEO()->description = 'Information about licensing and usage rights';
        $manager->persist($entry3);

        // Technical Support Entries
        $entry4 = new Entry();
        $entry4->setName('System Requirements');
        $entry4->setSlug('system-requirements');
        $entry4->setCategory($technicalCategory);
        $entry4->setState(ThreeStateStatusEnum::PUBLISHED);
        $entry4->setPublishDate(new \DateTime('-1 week'));
        $entry4->setAnswer('The sandbox requires PHP 8.2 or higher, Symfony 7.x, and either MySQL/MariaDB or SQLite for the database. For local development, we recommend using DDEV which handles all dependencies automatically. You\'ll also need Composer for PHP dependencies and npm for frontend assets.');
        $entry4->getSEO()->title = 'System Requirements for EasyAdmin Sandbox';
        $entry4->getSEO()->description = 'Technical requirements and dependencies';
        $manager->persist($entry4);

        $entry5 = new Entry();
        $entry5->setName('Troubleshooting Installation Issues');
        $entry5->setSlug('troubleshooting-installation');
        $entry5->setCategory($technicalCategory);
        $entry5->setState(ThreeStateStatusEnum::PUBLISHED);
        $entry5->setPublishDate(new \DateTime('-5 days'));
        $entry5->setAnswer('Common installation issues include: 1) Ensure DDEV is properly installed and running, 2) Check that ports 80 and 443 are available, 3) Verify PHP version compatibility, 4) Clear cache with `ddev console cache:clear`, 5) If database issues occur, try dropping and recreating: `ddev console doctrine:database:drop --force` followed by `ddev console doctrine:database:create`.');
        $entry5->getSEO()->title = 'Troubleshooting Installation Issues';
        $entry5->getSEO()->description = 'Common problems and solutions during setup';
        $manager->persist($entry5);

        // Billing & Pricing Entries
        $entry6 = new Entry();
        $entry6->setName('Commercial Licensing');
        $entry6->setSlug('commercial-licensing');
        $entry6->setCategory($billingCategory);
        $entry6->setState(ThreeStateStatusEnum::PUBLISHED);
        $entry6->setPublishDate(new \DateTime('-10 days'));
        $entry6->setAnswer('While the sandbox is free for evaluation, commercial use of the Easy* bundles in production requires appropriate licensing. Please contact our sales team at sales@adeliom.com for pricing information and commercial license options.');
        $entry6->getSEO()->title = 'Commercial Licensing Information';
        $entry6->getSEO()->description = 'Details about commercial licensing and pricing';
        $manager->persist($entry6);

        $entry7 = new Entry();
        $entry7->setName('Support Plans');
        $entry7->setSlug('support-plans');
        $entry7->setCategory($billingCategory);
        $entry7->setState(ThreeStateStatusEnum::PUBLISHED);
        $entry7->setPublishDate(new \DateTime('-1 week'));
        $entry7->setAnswer('We offer several support tiers: Community (free forum support), Professional (email support with 48h response), and Enterprise (priority support with dedicated account management). Support plans include documentation access, bug fixes, and upgrade assistance.');
        $entry7->getSEO()->title = 'Available Support Plans';
        $entry7->getSEO()->description = 'Overview of support options and service levels';
        $manager->persist($entry7);

        // Account Management Entries
        $entry8 = new Entry();
        $entry8->setName('Creating User Accounts');
        $entry8->setSlug('creating-user-accounts');
        $entry8->setCategory($accountCategory);
        $entry8->setState(ThreeStateStatusEnum::PUBLISHED);
        $entry8->setPublishDate(new \DateTime('-3 days'));
        $entry8->setAnswer('User accounts can be created through the admin interface at /admin/user or using the command line: `ddev console app:add-user`. The sandbox includes sample users with different roles: super admin (admin@example.com), regular admin, and standard user accounts.');
        $entry8->getSEO()->title = 'Creating and Managing User Accounts';
        $entry8->getSEO()->description = 'Guide to user account creation and management';
        $manager->persist($entry8);

        $entry9 = new Entry();
        $entry9->setName('Password Reset Process');
        $entry9->setSlug('password-reset-process');
        $entry9->setCategory($accountCategory);
        $entry9->setState(ThreeStateStatusEnum::PUBLISHED);
        $entry9->setPublishDate(new \DateTime('-2 days'));
        $entry9->setAnswer('Password reset functionality is built into the EasyAdminUser bundle. Users can request password resets through the login page, and administrators can reset passwords directly through the admin interface. All password reset tokens expire after 1 hour for security.');
        $entry9->getSEO()->title = 'Password Reset Process';
        $entry9->getSEO()->description = 'How to reset passwords for user accounts';
        $manager->persist($entry9);

        // Draft Entry (unpublished)
        $draftEntry = new Entry();
        $draftEntry->setName('Upcoming Features');
        $draftEntry->setSlug('upcoming-features');
        $draftEntry->setCategory($generalCategory);
        $draftEntry->setState(ThreeStateStatusEnum::UNPUBLISHED);
        $draftEntry->setAnswer('This entry is still being worked on and contains information about upcoming features.');
        $draftEntry->getSEO()->title = 'Upcoming Features - Draft';
        $draftEntry->getSEO()->description = 'This entry is in draft status';
        $manager->persist($draftEntry);

        $manager->flush();
    }
}