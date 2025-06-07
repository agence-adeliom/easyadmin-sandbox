<?php

namespace App\Tests\BlogBundle\EventListener;

use Adeliom\EasyBlogBundle\EventListener\SitemapSubscriber;
use App\Entity\EasyBlog\Category;
use App\Entity\EasyBlog\Post;
use App\Tests\BlogBundle\BlogTestCase;
use App\Tests\BlogBundle\SimpleManagerRegistry;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Service\UrlContainerInterface;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SitemapSubscriberTest extends BlogTestCase
{
    public function testPopulate(): void
    {
        $registry = new SimpleManagerRegistry($this->em);
        $generator = new class() implements UrlGeneratorInterface {
            public function generate(string $name, array $parameters = [], int $referenceType = self::ABSOLUTE_PATH): string
            {
                return '/' . $name . '/';
            }
            public function getContext(): \Symfony\Component\Routing\RequestContext { return new \Symfony\Component\Routing\RequestContext(); }
            public function setContext(\Symfony\Component\Routing\RequestContext $context): void {}
        };

        $container = new class() implements UrlContainerInterface {
            public array $urls = [];
            public function addUrl(\Presta\SitemapBundle\Sitemap\Url\Url $url, string $section): void
            {
                $this->urls[] = $url;
            }
        };

        $event = new SitemapPopulateEvent($container, $generator);

        $subscriber = new SitemapSubscriber(
            $generator,
            $registry->getRepository(Post::class),
            $registry->getRepository(Category::class),
            true
        );

        $subscriber->populate($event);

        $this->assertCount(3, $container->urls);
        $this->assertContainsOnlyInstancesOf(UrlConcrete::class, $container->urls);
    }
}
