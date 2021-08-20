<?php

namespace Adeliom\EasyBlogBundle\EventListener;

use Adeliom\EasyBlogBundle\Repository\BaseCategoryRepository;
use Adeliom\EasyBlogBundle\Repository\BasePostRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Service\UrlContainerInterface;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;

class SitemapSubscriber implements EventSubscriberInterface
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var BasePostRepository
     */
    private $postRepository;

    /**
     * @var BaseCategoryRepository
     */
    private $categoryRepository;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     * @param BasePostRepository  $postRepository
     * @param BaseCategoryRepository  $categoryRepository
     */
    public function __construct(UrlGeneratorInterface $urlGenerator, BasePostRepository $postRepository, BaseCategoryRepository $categoryRepository)
    {
        $this->urlGenerator = $urlGenerator;
        $this->postRepository = $postRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            SitemapPopulateEvent::ON_SITEMAP_POPULATE => 'populate',
        ];
    }

    /**
     * @param SitemapPopulateEvent $event
     */
    public function populate(SitemapPopulateEvent $event): void
    {
        $this->registerBlogCategoriesUrls($event->getUrlContainer());
        $this->registerBlogPostsUrls($event->getUrlContainer());
    }

    /**
     * @param UrlContainerInterface $urls
     */
    public function registerBlogCategoriesUrls(UrlContainerInterface $urls): void
    {
        $categories = $this->categoryRepository->getPublished();

        foreach ($categories as $category) {
            if($category->getSEO()->sitemap) {
                $urls->addUrl(
                    new UrlConcrete(
                        $this->urlGenerator->generate(
                            'easy_blog_index',
                            ['slugs' => $category->getTree()],
                            UrlGeneratorInterface::ABSOLUTE_URL
                        )
                    ),
                    'blog'
                );
            }
        }
    }

    /**
     * @param UrlContainerInterface $urls
     */
    public function registerBlogPostsUrls(UrlContainerInterface $urls): void
    {
        $posts = $this->postRepository->getPublished();

        foreach ($posts as $post) {
            if($post->getSEO()->sitemap) {
                $urls->addUrl(
                    new UrlConcrete(
                        $this->urlGenerator->generate(
                            'easy_blog_index',
                            ['slugs' => $post->getTree()],
                            UrlGeneratorInterface::ABSOLUTE_URL
                        )
                    ),
                    'blog'
                );
            }
        }
    }
}
