<?php

namespace App\Tests\BlogBundle\Controller;

use Adeliom\EasyBlogBundle\Controller\PostController;
use Adeliom\EasyBlogBundle\Event\EasyBlogCategoryEvent;
use App\Entity\EasyBlog\Category;
use App\Entity\EasyBlog\Post;
use App\Tests\BlogBundle\BlogTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Adeliom\EasySeoBundle\Services\BreadcrumbCollection;

class PostControllerTest extends BlogTestCase
{
    private function createController(): PostController
    {
        $controller = new PostController();
        $dispatcher = new EventDispatcher();
        $dispatcher->addListener(EasyBlogCategoryEvent::NAME, fn($e) => $e);

        $twig = new Environment(new ArrayLoader([
            '@EasyBlog/front/post.html.twig' => 'post',
        ]));
        $generator = new class() implements \Symfony\Component\Routing\Generator\UrlGeneratorInterface {
            public function generate(string $name, array $parameters = [], int $referenceType = self::ABSOLUTE_PATH): string { return '/'; }
            public function setContext(\Symfony\Component\Routing\RequestContext $context): void {}
            public function getContext(): \Symfony\Component\Routing\RequestContext { return new \Symfony\Component\Routing\RequestContext(); }
        };
        $breadcrumb = new BreadcrumbCollection();
        $breadcrumb->setGenerator($generator);

        $container = new Container();
        $container->set('event_dispatcher', $dispatcher);
        $container->set('twig', $twig);
        $container->set('easy_seo.breadcrumb', $breadcrumb);
        $controller->setContainer($container);

        return $controller;
    }

    public function testIndex(): void
    {
        $registry = new \App\Tests\BlogBundle\SimpleManagerRegistry($this->em);
        $category = $registry->getRepository(Category::class)->findOneBy(['slug' => 'cat']);
        $post = $registry->getRepository(Post::class)->findOneBy(['slug' => 'post-1']);

        $request = new Request([], [], [
            '_easy_blog_category' => $category,
            '_easy_blog_post' => $post,
        ]);

        $response = $this->createController()->index($request, 'cat', 'post-1');

        $this->assertSame(200, $response->getStatusCode());
    }
}
