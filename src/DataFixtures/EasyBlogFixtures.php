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
        $techCategory->getSEO()->title = 'Technology Articles - Latest Tech News and Tutorials';
        $techCategory->getSEO()->description = 'Stay updated with the latest technology trends, programming tutorials, and software development insights. Expert articles on web development, frameworks, and emerging tech.';
        $techCategory->getSEO()->keywords = 'technology, programming, web development, software, coding, tutorials, tech news, frameworks';
        $techCategory->getSEO()->cannonical = 'https://example.com/blog/category/technology';
        $techCategory->getSEO()->sitemap = true;
        $techCategory->getSEO()->robots = ['index', 'follow'];
        $manager->persist($techCategory);

        $businessCategory = new Category();
        $businessCategory->setName('Business');
        $businessCategory->setSlug('business');
        $businessCategory->setStatus(true);
        $businessCategory->getSEO()->title = 'Business Strategy & Insights - Expert Advice';
        $businessCategory->getSEO()->description = 'Discover proven business strategies, leadership tips, and industry insights to grow your company. Expert advice on entrepreneurship, management, and business development.';
        $businessCategory->getSEO()->keywords = 'business strategy, entrepreneurship, leadership, management, business development, startup, growth, success';
        $businessCategory->getSEO()->cannonical = 'https://example.com/blog/category/business';
        $businessCategory->getSEO()->sitemap = true;
        $businessCategory->getSEO()->robots = ['index', 'follow'];
        $manager->persist($businessCategory);

        $designCategory = new Category();
        $designCategory->setName('Design');
        $designCategory->setSlug('design');
        $designCategory->setStatus(true);
        $designCategory->getSEO()->title = 'Design Inspiration & Creative Ideas - UI/UX Design';
        $designCategory->getSEO()->description = 'Get inspired by the latest design trends, UI/UX best practices, and creative ideas. Learn about visual design, user experience, and design principles from experts.';
        $designCategory->getSEO()->keywords = 'design, UI/UX, user interface, user experience, visual design, creative, inspiration, design trends, graphics';
        $designCategory->getSEO()->cannonical = 'https://example.com/blog/category/design';
        $designCategory->getSEO()->sitemap = true;
        $designCategory->getSEO()->robots = ['index', 'follow'];
        $manager->persist($designCategory);

        // Create Blog Posts
        $post1 = new Post();
        $post1->setName('The Future of Web Development');
        $post1->setSlug('future-of-web-development');
        $post1->setCategory($techCategory);
        $post1->setState(ThreeStateStatusEnum::PUBLISHED);
        $post1->setPublishDate(new \DateTime('-1 week'));
        $post1->getSEO()->title = 'The Future of Web Development: Trends and Technologies 2024';
        $post1->getSEO()->description = 'Explore the latest trends in web development including WebAssembly, progressive web apps, AI integration, and modern JavaScript frameworks. Learn what technologies will shape the future.';
        $post1->getSEO()->keywords = 'web development, future trends, WebAssembly, progressive web apps, JavaScript, React, Vue.js, AI web development, modern frameworks';
        $post1->getSEO()->cannonical = 'https://example.com/blog/technology/future-of-web-development';
        $post1->getSEO()->sitemap = true;
        $post1->getSEO()->robots = ['index', 'follow'];
        $manager->persist($post1);

        $post2 = new Post();
        $post2->setName('Effective Business Strategies for 2024');
        $post2->setSlug('effective-business-strategies-2024');
        $post2->setCategory($businessCategory);
        $post2->setState(ThreeStateStatusEnum::PUBLISHED);
        $post2->setPublishDate(new \DateTime('-3 days'));
        $post2->getSEO()->title = '10 Effective Business Strategies for 2024 | Business Growth Guide';
        $post2->getSEO()->description = 'Discover proven business strategies for 2024 including digital transformation, sustainable practices, customer-centric approaches, and data-driven decision making for maximum growth.';
        $post2->getSEO()->keywords = 'business strategies 2024, business growth, digital transformation, sustainable business, customer experience, data-driven decisions, market analysis';
        $post2->getSEO()->cannonical = 'https://example.com/blog/business/effective-business-strategies-2024';
        $post2->getSEO()->sitemap = true;
        $post2->getSEO()->robots = ['index', 'follow'];
        $manager->persist($post2);

        $post3 = new Post();
        $post3->setName('Minimalist Design Principles');
        $post3->setSlug('minimalist-design-principles');
        $post3->setCategory($designCategory);
        $post3->setState(ThreeStateStatusEnum::PUBLISHED);
        $post3->setPublishDate(new \DateTime('-2 days'));
        $post3->getSEO()->title = 'Minimalist Design Principles: Complete Guide to Clean UI/UX';
        $post3->getSEO()->description = 'Master minimalist design with our comprehensive guide. Learn about white space, typography, color theory, and user experience principles that create elegant, functional designs.';
        $post3->getSEO()->keywords = 'minimalist design, UI/UX design principles, clean design, white space, typography, visual hierarchy, user experience, design aesthetics';
        $post3->getSEO()->cannonical = 'https://example.com/blog/design/minimalist-design-principles';
        $post3->getSEO()->sitemap = true;
        $post3->getSEO()->robots = ['index', 'follow'];
        $manager->persist($post3);

        $post4 = new Post();
        $post4->setName('Getting Started with Symfony 7');
        $post4->setSlug('getting-started-symfony-7');
        $post4->setCategory($techCategory);
        $post4->setState(ThreeStateStatusEnum::PUBLISHED);
        $post4->setPublishDate(new \DateTime('-1 day'));
        $post4->getSEO()->title = 'Getting Started with Symfony 7: Complete Beginner Tutorial';
        $post4->getSEO()->description = 'Learn Symfony 7 from scratch with this comprehensive tutorial. Cover installation, project setup, controllers, routing, Twig templates, and database integration.';
        $post4->getSEO()->keywords = 'Symfony 7 tutorial, PHP framework, Symfony beginner guide, MVC architecture, Twig templates, Doctrine ORM, web development PHP';
        $post4->getSEO()->cannonical = 'https://example.com/blog/technology/getting-started-symfony-7';
        $post4->getSEO()->sitemap = true;
        $post4->getSEO()->robots = ['index', 'follow'];
        $manager->persist($post4);

        $post5 = new Post();
        $post5->setName('Remote Work Best Practices');
        $post5->setSlug('remote-work-best-practices');
        $post5->setCategory($businessCategory);
        $post5->setState(ThreeStateStatusEnum::PUBLISHED);
        $post5->setPublishDate(new \DateTime('-5 hours'));
        $post5->getSEO()->title = 'Remote Work Best Practices: Ultimate Guide for 2024';
        $post5->getSEO()->description = 'Master remote work with proven strategies for productivity, communication, work-life balance, and team collaboration. Essential tips for remote workers and managers.';
        $post5->getSEO()->keywords = 'remote work, work from home, productivity tips, remote team management, virtual collaboration, work-life balance, remote communication';
        $post5->getSEO()->cannonical = 'https://example.com/blog/business/remote-work-best-practices';
        $post5->getSEO()->sitemap = true;
        $post5->getSEO()->robots = ['index', 'follow'];
        $manager->persist($post5);

        // Draft Post
        $draftPost = new Post();
        $draftPost->setName('Upcoming Design Trends');
        $draftPost->setSlug('upcoming-design-trends');
        $draftPost->setCategory($designCategory);
        $draftPost->setState(ThreeStateStatusEnum::UNPUBLISHED);
        $draftPost->getSEO()->title = 'Upcoming Design Trends 2024 - Draft Preview';
        $draftPost->getSEO()->description = 'Preview of upcoming design trends including 3D elements, sustainable design, and interactive experiences. This article is currently in draft status.';
        $draftPost->getSEO()->keywords = 'design trends 2024, 3D design, sustainable design, interactive design, future design, creative trends';
        $draftPost->getSEO()->cannonical = 'https://example.com/blog/design/upcoming-design-trends';
        $draftPost->getSEO()->sitemap = false;
        $draftPost->getSEO()->robots = ['noindex', 'nofollow'];
        $manager->persist($draftPost);

        // Scheduled Post
        $scheduledPost = new Post();
        $scheduledPost->setName('AI in Modern Business');
        $scheduledPost->setSlug('ai-in-modern-business');
        $scheduledPost->setCategory($businessCategory);
        $scheduledPost->setState(ThreeStateStatusEnum::PUBLISHED);
        $scheduledPost->setPublishDate(new \DateTime('+1 week'));
        $scheduledPost->getSEO()->title = 'AI in Modern Business: Complete Implementation Guide';
        $scheduledPost->getSEO()->description = 'Discover how AI is revolutionizing business operations. Learn about machine learning applications, automation, customer service AI, and strategic implementation for competitive advantage.';
        $scheduledPost->getSEO()->keywords = 'artificial intelligence business, AI automation, machine learning, business intelligence, AI implementation, digital transformation, future technology';
        $scheduledPost->getSEO()->cannonical = 'https://example.com/blog/business/ai-in-modern-business';
        $scheduledPost->getSEO()->sitemap = true;
        $scheduledPost->getSEO()->robots = ['index', 'follow'];
        $manager->persist($scheduledPost);

        $manager->flush();
    }
}