<?php

namespace App\Tests\Fixtures;

use Adeliom\EasyCommonBundle\Enum\ThreeStateStatusEnum;
use App\Entity\EasyBlog\Category;
use App\Entity\EasyBlog\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BlogFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $category = new Category();
        $category->setName('Category 1');
        $category->setSlug('cat');
        $category->setStatus(true);
        $manager->persist($category);

        $post1 = new Post();
        $post1->setName('Post 1');
        $post1->setSlug('post-1');
        $post1->setState(ThreeStateStatusEnum::PUBLISHED);
        $post1->setPublishDate((new \DateTimeImmutable('-1 day')));
        $post1->setCategory($category);
        $manager->persist($post1);

        $post2 = new Post();
        $post2->setName('Post 2');
        $post2->setSlug('post-2');
        $post2->setState(ThreeStateStatusEnum::PUBLISHED);
        $post2->setPublishDate((new \DateTimeImmutable('-1 day')));
        $post2->setCategory($category);
        $manager->persist($post2);

        $manager->flush();
    }
}
