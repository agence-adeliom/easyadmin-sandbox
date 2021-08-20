<?php

namespace Adeliom\EasyPageBundle\Controller;

use Adeliom\EasyCommonBundle\Helper\Hooks;
use Adeliom\EasyPageBundle\Entity\BasePageEntity;
use Adeliom\EasyPageBundle\Event\EasyPageEvent;
use Adeliom\EasyPageBundle\Repository\BasePageRepository;
use Adeliom\EasySeoBundle\Services\BreadCrumbCollection;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class BasePageController extends AbstractPageController
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var BasePageRepository
     */
    protected $pageRepository;

    public function setRepository(BasePageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    public function index(Request $request, string $slugs = '', string $_locale = null, EventDispatcherInterface $eventDispatcher, BreadCrumbCollection $breadcrumb): Response
    {
        if (preg_match('~/$~', $slugs)) {
            return $this->redirect($this->generateUrl('easy_page_index', ['slugs' => rtrim($slugs, '/')]));
        }

        $template = '@EasyPage/front/index.html.twig';

        $this->request = $request;
        $this->request->setLocale($_locale ?: $this->request->getLocale());

        $slugsArray = preg_split('~/~', $slugs, -1, PREG_SPLIT_NO_EMPTY);

        $pages = $this->getPages($slugsArray);

        $currentPage = $this->getCurrentPage($pages, $slugsArray);

        // If we have slugs and the current page is homepage,
        //  we redirect to homepage for "better" url and SEO management.
        // Example: if "/home" is a homepage, "/home" url is redirected to "/".
        if ($slugs && $currentPage->isHomepage()) {
            $params = ['slugs' => ''];
            return $this->redirect($this->generateUrl('easy_page_index', $params));
        }

        if ($currentPage->getTemplate() && $this->get('twig')->getLoader()->exists('@EasyPage/front/pages/type-' . $currentPage->getTemplate() . '.html.twig')) {
            $template = '@EasyPage/front/pages/type-' . $currentPage->getTemplate() . '.html.twig';
        }

        if ($currentPage->getTemplate() && $this->get('twig')->getLoader()->exists('pages/type-' . $currentPage->getTemplate() . '.html.twig')) {
            $template = 'pages/type-' . $currentPage->getTemplate() . '.html.twig';
        }

        $breadcrumb->addRouteItem('homepage', ['route' => "easy_page_index"]);
        if (!$currentPage->isHomepage()){
            foreach ($pages as $page){
                $breadcrumb->addRouteItem($page->getName(), ['route' => "easy_page_index", 'params' => ['slugs' => $page->getTree()]]);
            }
        }


        $args = [
            'pages' => $pages,
            'page'  => $currentPage,
            'breadcrumb' => $breadcrumb
        ];
        $event = new EasyPageEvent($currentPage, $args, $template);
        /**
         * @var EasyPageEvent $result;
         */
        $result = $eventDispatcher->dispatch($event, EasyPageEvent::NAME);

        return $this->render($result->getTemplate(), $result->getArgs());
    }

    /**
     * Retrieves the page list based on slugs.
     * Also checks the hierarchy of the different pages.
     *
     * @param string[] $slugsArray
     *
     * @return BasePageEntity[]
     */
    protected function getPages(array $slugsArray = []): array
    {
        /** @var BasePageEntity[] $pages */
        $pages = $this->pageRepository
            ->findFrontPages($slugsArray, $this->request->getHost(), $this->request->getLocale());

        if (!count($pages) || (count($slugsArray) && count($pages) !== count($slugsArray))) {
            throw $this->createNotFoundException(count($slugsArray)
                ? 'Page not found'
                : 'No homepage has been configured. Please check your existing pages or create a homepage in your application.');
        }

        return $pages;
    }

    /**
     * Retrieves the current page based on page list and entered slugs.
     *
     * @param BasePageEntity[] $pages
     * @param string[]  $slugsArray
     *
     * @return BasePageEntity
     */
    protected function getCurrentPage(array $pages, array $slugsArray): BasePageEntity
    {
        if (count($pages) === count($slugsArray)) {
            $currentPage = $this->getFinalTreeElement($slugsArray, $pages);
        } else {
            $currentPage = current($pages);
        }

        return $currentPage;
    }
}
