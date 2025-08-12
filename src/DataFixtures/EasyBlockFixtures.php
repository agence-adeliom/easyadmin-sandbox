<?php

namespace App\DataFixtures;

use App\Entity\EasyBlock\Block;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EasyBlockFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Header Block
        $headerBlock = new Block();
        $headerBlock->setName('Header Block');
        $headerBlock->setKey('header_main');
        $headerBlock->setType('HeaderType');
        $headerBlock->setSettings([
            'title' => 'Welcome to Our Website',
            'subtitle' => 'Your trusted partner for digital solutions',
            'image' => '/images/header-bg.jpg',
            'cta_text' => 'Get Started',
            'cta_url' => '/contact'
        ]);
        $headerBlock->setStatus(true);
        $manager->persist($headerBlock);

        // Two Picto Block
        $pictoBlock = new Block();
        $pictoBlock->setName('Features Section');
        $pictoBlock->setKey('features_home');
        $pictoBlock->setType('TwoPictoType');
        $pictoBlock->setSettings([
            'left_icon' => 'fas fa-cog',
            'left_title' => 'Easy to Use',
            'left_description' => 'Our solution is designed to be user-friendly and intuitive.',
            'right_icon' => 'fas fa-rocket',
            'right_title' => 'Fast Performance',
            'right_description' => 'Experience lightning-fast performance with our optimized platform.'
        ]);
        $pictoBlock->setStatus(true);
        $manager->persist($pictoBlock);

        // Embed Block
        $embedBlock = new Block();
        $embedBlock->setName('Video Introduction');
        $embedBlock->setKey('video_intro');
        $embedBlock->setType('EmbedType');
        $embedBlock->setSettings([
            'embed_code' => '<iframe width="560" height="315" src="https://www.youtube.com/embed/dQw4w9WgXcQ" frameborder="0" allowfullscreen></iframe>',
            'title' => 'Company Introduction Video',
            'description' => 'Learn more about our company and our mission.'
        ]);
        $embedBlock->setStatus(true);
        $manager->persist($embedBlock);

        // Collection Block
        $collectionBlock = new Block();
        $collectionBlock->setName('Team Members');
        $collectionBlock->setKey('team_showcase');
        $collectionBlock->setType('CollectionBlockType');
        $collectionBlock->setSettings([
            'title' => 'Meet Our Team',
            'items' => [
                [
                    'name' => 'John Doe',
                    'position' => 'CEO & Founder',
                    'bio' => 'John has over 15 years of experience in digital solutions.',
                    'image' => '/images/team/john.jpg'
                ],
                [
                    'name' => 'Jane Smith',
                    'position' => 'CTO',
                    'bio' => 'Jane leads our technical team with expertise in modern technologies.',
                    'image' => '/images/team/jane.jpg'
                ],
                [
                    'name' => 'Bob Johnson',
                    'position' => 'Lead Developer',
                    'bio' => 'Bob brings innovative solutions to complex technical challenges.',
                    'image' => '/images/team/bob.jpg'
                ]
            ]
        ]);
        $collectionBlock->setStatus(true);
        $manager->persist($collectionBlock);

        // Disabled Block (for testing)
        $disabledBlock = new Block();
        $disabledBlock->setName('Disabled Block');
        $disabledBlock->setKey('disabled_test');
        $disabledBlock->setType('HeaderType');
        $disabledBlock->setSettings([
            'title' => 'This block is disabled',
            'subtitle' => 'It should not appear on the frontend'
        ]);
        $disabledBlock->setStatus(false);
        $manager->persist($disabledBlock);

        $manager->flush();
    }
}