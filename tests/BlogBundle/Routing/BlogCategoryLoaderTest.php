<?php

namespace App\Tests\BlogBundle\Routing;

use Adeliom\EasyBlogBundle\Routing\BlogCategoryLoader;
use App\Tests\BlogBundle\BlogTestCase;
use Symfony\Component\Routing\RouteCollection;

class BlogCategoryLoaderTest extends BlogTestCase
{
    public function testSupports(): void
    {
        $repo = new \App\Repository\EasyBlog\CategoryRepository(new \App\Tests\BlogBundle\SimpleManagerRegistry($this->em));
        $loader = new BlogCategoryLoader('Controller', '', $repo, ['root_path' => '/blog']);
        $this->assertTrue($loader->supports(null, 'easy_blog_category'));
        $this->assertFalse($loader->supports(null, 'foo'));
    }

    public function testLoad(): void
    {
        $repo = new \App\Repository\EasyBlog\CategoryRepository(new \App\Tests\BlogBundle\SimpleManagerRegistry($this->em));
        $loader = new BlogCategoryLoader('Controller', '', $repo, ['root_path' => '/blog']);
        $collection = $loader->load([], 'easy_blog_category');
        $this->assertInstanceOf(RouteCollection::class, $collection);
        $route = $collection->get('easy_blog_category_index');
        $this->assertSame('/blog/{category}', $route->getPath());
    }
}
