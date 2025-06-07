<?php

namespace App\Tests\BlogBundle\Repository;

use App\Entity\EasyBlog\Category;
use App\Entity\EasyBlog\Post;
use App\Repository\EasyBlog\CategoryRepository;
use App\Repository\EasyBlog\PostRepository;
use App\Tests\BlogBundle\BlogTestCase;
use App\Tests\BlogBundle\SimpleManagerRegistry;

class PostRepositoryTest extends BlogTestCase
{
    public function testGetPublished(): void
    {
        $repo = new PostRepository(new \App\Tests\BlogBundle\SimpleManagerRegistry($this->em));
        $posts = $repo->getPublished();
        $this->assertCount(2, $posts);
    }

    public function testGetByCategory(): void
    {
        $registry = new \App\Tests\BlogBundle\SimpleManagerRegistry($this->em);
        $repo = new PostRepository($registry);
        $cat = (new CategoryRepository($registry))->findOneBy(['slug' => 'cat']);
        $posts = $repo->getByCategory($cat);
        $this->assertCount(2, $posts);
    }

    public function testGetBySlug(): void
    {
        $registry = new \App\Tests\BlogBundle\SimpleManagerRegistry($this->em);
        $repo = new PostRepository($registry);
        $cat = (new CategoryRepository($registry))->findOneBy(['slug' => 'cat']);
        $post = $repo->getBySlug('post-1', $cat);
        $this->assertInstanceOf(Post::class, $post);
        $this->assertSame('post-1', $post->getSlug());
    }
}
