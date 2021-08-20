<?php

namespace Adeliom\EasyBlogBundle\Controller;

use Adeliom\EasyBlogBundle\Event\EasyBlogCategoryEvent;
use Adeliom\EasyBlogBundle\Event\EasyBlogPostEvent;
use Adeliom\EasyBlogBundle\Repository\BaseCategoryRepository;
use Adeliom\EasyBlogBundle\Repository\BasePostRepository;
use Adeliom\EasySeoBundle\Services\BreadCrumbCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class BasePostController extends AbstractController
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

    public function index(Request $request, string $category = '', string $post = '', string $_locale = null, EventDispatcherInterface $eventDispatcher, BreadCrumbCollection $breadcrumb): Response
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->breadcrumb = $breadcrumb;

        $this->request = $request;
        $this->request->setLocale($_locale ?: $this->request->getLocale());

        $this->breadcrumb->addRouteItem('homepage', ['route' => "easy_page_index"]);
        $this->breadcrumb->addRouteItem('blog', ['route' => "easy_blog_category_index"]);


        $template = '@EasyBlog/front/post.html.twig';

        $category = $this->categoryRepository->getBySlug($category);
        $post = $this->postRepository->getBySlug($post, $category);

        $this->breadcrumb->addRouteItem($category->getName(), ['route' => "easy_blog_category_index", 'params' => ['category' => $category->getSlug()]]);
        $this->breadcrumb->addRouteItem($post->getName(), ['route' => "easy_blog_post_index", 'params' => ['category' => $post->getCategory()->getSlug(), 'post' => $post->getSlug()]]);

        $args = [
            'category' => $category,
            'post'  => $post,
            'breadcrumb' => $breadcrumb
        ];
        $event = new EasyBlogPostEvent($post, $args, $template);
        /**
         * @var EasyBlogCategoryEvent $result;
         */
        $result = $this->eventDispatcher->dispatch($event, EasyBlogCategoryEvent::NAME);

        return $this->render($result->getTemplate(), $result->getArgs());
    }

}
