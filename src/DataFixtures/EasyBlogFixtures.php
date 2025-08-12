<?php

namespace App\DataFixtures;

use Adeliom\EasyCommonBundle\Enum\ThreeStateStatusEnum;
use App\Entity\EasyBlog\Category;
use App\Entity\EasyBlog\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EasyBlogFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Create Categories
        $techCategory = new Category();
        $techCategory->setName('Technology');
        $techCategory->setSlug('technology');
        $techCategory->setStatus(true);
        $techCategory->getSEO()->title = 'Technology Articles';
        $techCategory->getSEO()->description = 'Latest articles about technology and innovation';
        $manager->persist($techCategory);

        $businessCategory = new Category();
        $businessCategory->setName('Business');
        $businessCategory->setSlug('business');
        $businessCategory->setStatus(true);
        $businessCategory->getSEO()->title = 'Business Insights';
        $businessCategory->getSEO()->description = 'Business tips and industry insights';
        $manager->persist($businessCategory);

        $designCategory = new Category();
        $designCategory->setName('Design');
        $designCategory->setSlug('design');
        $designCategory->setStatus(true);
        $designCategory->getSEO()->title = 'Design Inspiration';
        $designCategory->getSEO()->description = 'Creative design ideas and inspiration';
        $manager->persist($designCategory);

        // Create Blog Posts
        $post1 = new Post();
        $post1->setName('The Future of Web Development');
        $post1->setSlug('future-of-web-development');
        $post1->setCategory($techCategory);
        $post1->setState(ThreeStateStatusEnum::PUBLISHED);
        $post1->setPublishDate(new \DateTime('-1 week'));
        $post1->getSEO()->title = 'The Future of Web Development - Tech Blog';
        $post1->getSEO()->description = 'Exploring upcoming trends and technologies in web development';
        $manager->persist($post1);

        $post2 = new Post();
        $post2->setName('Effective Business Strategies for 2024');
        $post2->setSlug('effective-business-strategies-2024');
        $post2->setCategory($businessCategory);
        $post2->setState(ThreeStateStatusEnum::PUBLISHED);
        $post2->setPublishDate(new \DateTime('-3 days'));
        $post2->getSEO()->title = 'Effective Business Strategies for 2024';
        $post2->getSEO()->description = 'Key strategies for business success in the modern era';
        $manager->persist($post2);

        $post3 = new Post();
        $post3->setName('Minimalist Design Principles');
        $post3->setSlug('minimalist-design-principles');
        $post3->setCategory($designCategory);
        $post3->setState(ThreeStateStatusEnum::PUBLISHED);
        $post3->setPublishDate(new \DateTime('-2 days'));
        $post3->getSEO()->title = 'Minimalist Design Principles - Design Blog';
        $post3->getSEO()->description = 'Understanding and applying minimalist design principles';
        $manager->persist($post3);

        $post4 = new Post();
        $post4->setName('Getting Started with Symfony 7');
        $post4->setSlug('getting-started-symfony-7');
        $post4->setCategory($techCategory);
        $post4->setState(ThreeStateStatusEnum::PUBLISHED);
        $post4->setPublishDate(new \DateTime('-1 day'));
        $post4->getSEO()->title = 'Getting Started with Symfony 7';
        $post4->getSEO()->description = 'A beginner guide to Symfony 7 development';
        $manager->persist($post4);

        $post5 = new Post();
        $post5->setName('Remote Work Best Practices');
        $post5->setSlug('remote-work-best-practices');
        $post5->setCategory($businessCategory);
        $post5->setState(ThreeStateStatusEnum::PUBLISHED);
        $post5->setPublishDate(new \DateTime('-5 hours'));
        $post5->getSEO()->title = 'Remote Work Best Practices';
        $post5->getSEO()->description = 'Tips for effective remote work and team collaboration';
        $manager->persist($post5);

        // Draft Post
        $draftPost = new Post();
        $draftPost->setName('Upcoming Design Trends');
        $draftPost->setSlug('upcoming-design-trends');
        $draftPost->setCategory($designCategory);
        $draftPost->setState(ThreeStateStatusEnum::UNPUBLISHED);
        $draftPost->getSEO()->title = 'Upcoming Design Trends - Draft';
        $draftPost->getSEO()->description = 'This post is still in draft mode';
        $manager->persist($draftPost);

        // Scheduled Post
        $scheduledPost = new Post();
        $scheduledPost->setName('AI in Modern Business');
        $scheduledPost->setSlug('ai-in-modern-business');
        $scheduledPost->setCategory($businessCategory);
        $scheduledPost->setState(ThreeStateStatusEnum::PUBLISHED);
        $scheduledPost->setPublishDate(new \DateTime('+1 week'));
        $scheduledPost->getSEO()->title = 'AI in Modern Business';
        $scheduledPost->getSEO()->description = 'How artificial intelligence is transforming business operations';
        $manager->persist($scheduledPost);

        $manager->flush();
    }
}