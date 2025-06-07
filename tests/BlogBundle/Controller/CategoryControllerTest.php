<?php

namespace App\Tests\BlogBundle\Controller;

use Adeliom\EasyBlogBundle\Controller\CategoryController;
use Adeliom\EasyBlogBundle\Event\EasyBlogCategoryEvent;
use App\Entity\EasyBlog\Category;
use App\Tests\BlogBundle\BlogTestCase;
use App\Tests\BlogBundle\SimpleManagerRegistry;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Adeliom\EasySeoBundle\Services\BreadcrumbCollection;

class CategoryControllerTest extends BlogTestCase
{
    private function createController(): CategoryController
    {
        $registry = new SimpleManagerRegistry($this->em);
        $controller = new CategoryController($registry);

        $dispatcher = new EventDispatcher();
        $dispatcher->addListener(EasyBlogCategoryEvent::NAME, fn(EasyBlogCategoryEvent $e) => $e);

        $twig = new Environment(new ArrayLoader([
            '@EasyBlog/front/category.html.twig' => 'category',
            '@EasyBlog/front/root.html.twig' => 'root',
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
        $container->set('parameter_bag', new ParameterBag([
            'easy_blog.post.class' => \App\Entity\EasyBlog\Post::class,
            'easy_blog.category.class' => \App\Entity\EasyBlog\Category::class,
        ]));
        $controller->setContainer($container);

        return $controller;
    }

    public function testIndex(): void
    {
        $repo = (new SimpleManagerRegistry($this->em))->getRepository(Category::class);
        $category = $repo->findOneBy(['slug' => 'cat']);
        $request = new Request([], [], ['_easy_blog_category' => $category]);

        $response = $this->createController()->index($request, 'cat');
        $this->assertSame(200, $response->getStatusCode());
    }

    public function testBlogRoot(): void
    {
        $request = new Request([], [], ['_easy_blog_root' => true]);

        $response = $this->createController()->blogRoot($request);
        $this->assertSame(200, $response->getStatusCode());
    }
}
