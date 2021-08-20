<?php

namespace Adeliom\EasyBlogBundle\Controller;

use Adeliom\EasyBlogBundle\Event\EasyBlogCategoryEvent;
use Adeliom\EasyBlogBundle\Repository\BaseCategoryRepository;
use Adeliom\EasyBlogBundle\Repository\BasePostRepository;
use Adeliom\EasySeoBundle\Entity\SEO;
use Adeliom\EasySeoBundle\Services\BreadCrumbCollection;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class BaseCategoryController extends AbstractController
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var BaseCategoryRepository
     */
    protected $categoryRepository;

    /**
     * @var BasePostRepository
     */
    protected $postRepository;


    /**
     * @var BasePostRepository
     */
    protected $eventDispatcher;

    /**
     * @var BreadCrumbCollection
     */
    protected $breadcrumb;

    public function setRepositories(BaseCategoryRepository $categoryRepository, BasePostRepository $postRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->postRepository = $postRepository;
    }

    public function index(Request $request, string $category = '', string $_locale = null, EventDispatcherInterface $eventDispatcher, BreadCrumbCollection $breadcrumb): Response
    {

        $this->eventDispatcher = $eventDispatcher;
        $this->breadcrumb = $breadcrumb;

        $this->request = $request;
        $this->request->setLocale($_locale ?: $this->request->getLocale());

        $this->breadcrumb->addRouteItem('homepage', ['route' => "easy_page_index"]);
        $this->breadcrumb->addRouteItem('blog', ['route' => "easy_blog_category_index"]);

        if(empty($category)){
            return $this->blogRoot();
        }

        $template = '@EasyBlog/front/category.html.twig';

        $category = $this->categoryRepository->getBySlug($category);
        $postsQueryBuilder = $this->postRepository->getByCategory($category, true);

        $pagerfanta = new Pagerfanta(
            new QueryAdapter($postsQueryBuilder)
        );

        $this->breadcrumb->addRouteItem($category->getName(), ['route' => "easy_blog_category_index", 'params' => ['category' => $category->getSlug()]]);

        $args = [
            'category' => $category,
            'posts'  => $pagerfanta,
            'breadcrumb' => $breadcrumb
        ];
        $event = new EasyBlogCategoryEvent($category, $args, $template);
        /**
         * @var EasyBlogCategoryEvent $result;
         */
        $result = $this->eventDispatcher->dispatch($event, EasyBlogCategoryEvent::NAME);

        return $this->render($result->getTemplate(), $result->getArgs());
    }

    public function blogRoot() : Response
    {
        $template = '@EasyBlog/front/root.html.twig';

        $categories = $this->categoryRepository->getPublished();
        $postsQueryBuilder = $this->postRepository->getPublished(true);

        $pagerfanta = new Pagerfanta(
            new QueryAdapter($postsQueryBuilder)
        );

        $args = [
            'categories' => $categories,
            'posts'  => $pagerfanta,
            'page'  => [
                'name' => null,
                'seo' => new SEO()
            ],
            'breadcrumb' => $this->breadcrumb
        ];
        $event = new EasyBlogCategoryEvent(null, $args, $template);
        /**
         * @var EasyBlogCategoryEvent $result;
         */
        $result = $this->eventDispatcher->dispatch($event, EasyBlogCategoryEvent::NAME);

        return $this->render($result->getTemplate(), $result->getArgs());
    }
}
