<?php

namespace Adeliom\EasyPageBundle\EventListener;

use Adeliom\EasyPageBundle\Repository\BasePageRepository;
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
     * @var BasePageRepository
     */
    private $repository;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     * @param BasePageRepository    $repository
     */
    public function __construct(UrlGeneratorInterface $urlGenerator, BasePageRepository $repository)
    {
        $this->urlGenerator = $urlGenerator;
        $this->repository = $repository;
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
        $this->registerBlogPostsUrls($event->getUrlContainer());
    }

    /**
     * @param UrlContainerInterface $urls
     */
    public function registerBlogPostsUrls(UrlContainerInterface $urls): void
    {
        $pages = $this->repository->getPublished();

        foreach ($pages as $page) {
            if($page->getSEO()->sitemap) {
                $urls->addUrl(
                    new UrlConcrete(
                        $this->urlGenerator->generate(
                            'easy_page_index',
                            ['slugs' => $page->getTree()],
                            UrlGeneratorInterface::ABSOLUTE_URL
                        )
                    ),
                    'pages'
                );
            }
        }
    }
}
