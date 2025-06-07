<?php

namespace App\Tests\BlogBundle\Routing;

use Adeliom\EasyBlogBundle\Routing\BlogPostLoader;
use App\Tests\BlogBundle\BlogTestCase;
use Symfony\Component\Routing\RouteCollection;

class BlogPostLoaderTest extends BlogTestCase
{
    public function testSupports(): void
    {
        $repo = new \App\Repository\EasyBlog\PostRepository(new \App\Tests\BlogBundle\SimpleManagerRegistry($this->em));
        $loader = new BlogPostLoader('Controller', '', $repo, ['root_path' => '/blog']);
        $this->assertTrue($loader->supports(null, 'easy_blog_post'));
        $this->assertFalse($loader->supports(null, 'foo'));
    }

    public function testLoad(): void
    {
        $repo = new \App\Repository\EasyBlog\PostRepository(new \App\Tests\BlogBundle\SimpleManagerRegistry($this->em));
        $loader = new BlogPostLoader('Controller', '', $repo, ['root_path' => '/blog']);
        $collection = $loader->load([], 'easy_blog_post');
        $route = $collection->get('easy_blog_post_index');
        $this->assertSame('/blog/{category}/{post}', $route->getPath());
    }
}
