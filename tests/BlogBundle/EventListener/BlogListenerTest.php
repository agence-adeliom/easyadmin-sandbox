<?php

namespace App\Tests\BlogBundle\EventListener;

use Adeliom\EasyBlogBundle\EventListener\BlogListener;
use App\Tests\BlogBundle\BlogTestCase;
use App\Tests\BlogBundle\SimpleManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class BlogListenerTest extends BlogTestCase
{
    public function testSetRequestLayoutForPost(): void
    {
        $registry = new SimpleManagerRegistry($this->em);
        $listener = new BlogListener(
            $registry->getRepository(\App\Entity\EasyBlog\Post::class),
            $registry->getRepository(\App\Entity\EasyBlog\Category::class),
            ['root_path' => '/blog']
        );

        $request = new Request([], [], [], [], [], ['HTTP_HOST' => 'localhost', 'REQUEST_URI' => '/blog/cat/post-1']);
        $kernel = $this->createMock(HttpKernelInterface::class);
        $event = new RequestEvent($kernel, $request, HttpKernelInterface::MAIN_REQUEST);

        $listener->setRequestLayout($event);

        $this->assertTrue($request->attributes->has('_easy_blog_category'));
        $this->assertTrue($request->attributes->has('_easy_blog_post'));
    }

    public function testSetRequestLayoutForRoot(): void
    {
        $registry = new SimpleManagerRegistry($this->em);
        $listener = new BlogListener(
            $registry->getRepository(\App\Entity\EasyBlog\Post::class),
            $registry->getRepository(\App\Entity\EasyBlog\Category::class),
            ['root_path' => '/blog']
        );

        $request = new Request([], [], [], [], [], ['HTTP_HOST' => 'localhost', 'REQUEST_URI' => '/blog']);
        $kernel = $this->createMock(HttpKernelInterface::class);
        $event = new RequestEvent($kernel, $request, HttpKernelInterface::MAIN_REQUEST);

        $listener->setRequestLayout($event);

        $this->assertTrue($request->attributes->has('_easy_blog_root'));
    }
}
