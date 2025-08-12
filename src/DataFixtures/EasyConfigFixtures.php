<?php

namespace App\DataFixtures;

use Adeliom\EasyConfigBundle\Enum\EasyConfigEnum;
use App\Entity\EasyConfig\Config;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EasyConfigFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Site configuration
        $siteTitle = new Config();
        $siteTitle->setKey('site_title');
        $siteTitle->setName('Site Title');
        $siteTitle->setDescription('The main title of the website');
        $siteTitle->setType(EasyConfigEnum::TEXT->value);
        $siteTitle->setValue('Adeliom EasyAdmin Sandbox');
        $manager->persist($siteTitle);

        $siteDescription = new Config();
        $siteDescription->setKey('site_description');
        $siteDescription->setName('Site Description');
        $siteDescription->setDescription('Short description of the website');
        $siteDescription->setType(EasyConfigEnum::TEXTAREA->value);
        $siteDescription->setValue('A comprehensive sandbox for demonstrating Adeliom\'s Easy* bundle ecosystem');
        $manager->persist($siteDescription);

        // Contact information
        $contactEmail = new Config();
        $contactEmail->setKey('contact_email');
        $contactEmail->setName('Contact Email');
        $contactEmail->setDescription('Main contact email address');
        $contactEmail->setType(EasyConfigEnum::EMAIL->value);
        $contactEmail->setValue('contact@adeliom.com');
        $manager->persist($contactEmail);

        $contactPhone = new Config();
        $contactPhone->setKey('contact_phone');
        $contactPhone->setName('Contact Phone');
        $contactPhone->setDescription('Main contact phone number');
        $contactPhone->setType(EasyConfigEnum::TEXT->value);
        $contactPhone->setValue('+33 1 23 45 67 89');
        $manager->persist($contactPhone);

        // Social media links
        $facebookUrl = new Config();
        $facebookUrl->setKey('facebook_url');
        $facebookUrl->setName('Facebook URL');
        $facebookUrl->setDescription('Facebook page URL');
        $facebookUrl->setType(EasyConfigEnum::TEXT->value);
        $facebookUrl->setValue('https://facebook.com/adeliom');
        $manager->persist($facebookUrl);

        $twitterUrl = new Config();
        $twitterUrl->setKey('twitter_url');
        $twitterUrl->setName('Twitter URL');
        $twitterUrl->setDescription('Twitter profile URL');
        $twitterUrl->setType(EasyConfigEnum::TEXT->value);
        $twitterUrl->setValue('https://twitter.com/adeliom');
        $manager->persist($twitterUrl);

        $linkedinUrl = new Config();
        $linkedinUrl->setKey('linkedin_url');
        $linkedinUrl->setName('LinkedIn URL');
        $linkedinUrl->setDescription('LinkedIn company page URL');
        $linkedinUrl->setType(EasyConfigEnum::TEXT->value);
        $linkedinUrl->setValue('https://linkedin.com/company/adeliom');
        $manager->persist($linkedinUrl);

        // Feature toggles
        $maintenanceMode = new Config();
        $maintenanceMode->setKey('maintenance_mode');
        $maintenanceMode->setName('Maintenance Mode');
        $maintenanceMode->setDescription('Enable maintenance mode for the site');
        $maintenanceMode->setType(EasyConfigEnum::BOOLEAN->value);
        $maintenanceMode->setValue('0');
        $manager->persist($maintenanceMode);

        $enableBlog = new Config();
        $enableBlog->setKey('enable_blog');
        $enableBlog->setName('Enable Blog');
        $enableBlog->setDescription('Enable/disable the blog feature');
        $enableBlog->setType(EasyConfigEnum::BOOLEAN->value);
        $enableBlog->setValue('1');
        $manager->persist($enableBlog);

        $enableComments = new Config();
        $enableComments->setKey('enable_comments');
        $enableComments->setName('Enable Comments');
        $enableComments->setDescription('Allow comments on blog posts');
        $enableComments->setType(EasyConfigEnum::BOOLEAN->value);
        $enableComments->setValue('1');
        $manager->persist($enableComments);

        // Appearance settings
        $primaryColor = new Config();
        $primaryColor->setKey('primary_color');
        $primaryColor->setName('Primary Color');
        $primaryColor->setDescription('Main color theme for the website');
        $primaryColor->setType(EasyConfigEnum::COLOR->value);
        $primaryColor->setValue('#007bff');
        $manager->persist($primaryColor);

        $secondaryColor = new Config();
        $secondaryColor->setKey('secondary_color');
        $secondaryColor->setName('Secondary Color');
        $secondaryColor->setDescription('Secondary color theme for the website');
        $secondaryColor->setType(EasyConfigEnum::COLOR->value);
        $secondaryColor->setValue('#6c757d');
        $manager->persist($secondaryColor);

        // Numeric settings
        $postsPerPage = new Config();
        $postsPerPage->setKey('posts_per_page');
        $postsPerPage->setName('Posts Per Page');
        $postsPerPage->setDescription('Number of blog posts to display per page');
        $postsPerPage->setType(EasyConfigEnum::NUMBER->value);
        $postsPerPage->setValue('10');
        $manager->persist($postsPerPage);

        $maxUploadSize = new Config();
        $maxUploadSize->setKey('max_upload_size');
        $maxUploadSize->setName('Max Upload Size (MB)');
        $maxUploadSize->setDescription('Maximum file upload size in megabytes');
        $maxUploadSize->setType(EasyConfigEnum::NUMBER->value);
        $maxUploadSize->setValue('50');
        $manager->persist($maxUploadSize);

        // Rich text content
        $footerContent = new Config();
        $footerContent->setKey('footer_content');
        $footerContent->setName('Footer Content');
        $footerContent->setDescription('Rich text content for the website footer');
        $footerContent->setType(EasyConfigEnum::WYSIWYG->value);
        $footerContent->setValue('<p>&copy; 2024 Adeliom. All rights reserved. | <a href="/privacy">Privacy Policy</a> | <a href="/terms">Terms of Service</a></p>');
        $manager->persist($footerContent);

        // JSON configuration
        $apiSettings = new Config();
        $apiSettings->setKey('api_settings');
        $apiSettings->setName('API Settings');
        $apiSettings->setDescription('Configuration for external API integrations');
        $apiSettings->setType(EasyConfigEnum::JSON->value);
        $apiSettings->setValue('{"timeout": 30, "retries": 3, "base_url": "https://api.example.com"}');
        $manager->persist($apiSettings);

        // Date/time settings
        $launchDate = new Config();
        $launchDate->setKey('launch_date');
        $launchDate->setName('Launch Date');
        $launchDate->setDescription('Official website launch date');
        $launchDate->setType(EasyConfigEnum::DATE->value);
        $launchDate->setValue('2024-01-15');
        $manager->persist($launchDate);

        $maintenanceStart = new Config();
        $maintenanceStart->setKey('maintenance_start_time');
        $maintenanceStart->setName('Maintenance Start Time');
        $maintenanceStart->setDescription('Daily maintenance window start time');
        $maintenanceStart->setType(EasyConfigEnum::TIME->value);
        $maintenanceStart->setValue('02:00:00');
        $manager->persist($maintenanceStart);

        $lastBackup = new Config();
        $lastBackup->setKey('last_backup');
        $lastBackup->setName('Last Backup');
        $lastBackup->setDescription('Date and time of the last backup');
        $lastBackup->setType(EasyConfigEnum::DATETIME->value);
        $lastBackup->setValue((new \DateTime())->format('Y-m-d H:i:s'));
        $manager->persist($lastBackup);

        $manager->flush();
    }
}