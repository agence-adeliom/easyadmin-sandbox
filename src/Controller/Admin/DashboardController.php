<?php

namespace App\Controller\Admin;

use Adeliom\EasyAdminUserBundle\Controller\Admin\EasyAdminUserTrait;
use Adeliom\EasyConfigBundle\Controller\Admin\EasyConfigTrait;
use Adeliom\EasyRedirectBundle\Admin\EasyRedirectTrait;
use Adeliom\EasyShopBundle\Admin\EasyShopDashboardTrait;
use App\Entity\EasyAdmin\User;
use App\Entity\EasyBlock\Block;
use App\Entity\EasyBlog\Category;
use App\Entity\EasyBlog\Post;
use App\Entity\EasyPage\Page;
use App\Entity\EasyRedirect\NotFound;
use App\Entity\EasyRedirect\Redirect;
use App\Entity\Menu\Menu;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    use EasyShopDashboardTrait;
    use EasyAdminUserTrait;
    use EasyConfigTrait;
    use EasyRedirectTrait;

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('My Project Name')
            ;
    }

    public static function getSubscribedServices()
    {
        return array_merge(parent::getSubscribedServices(), self::SYLIUS_SERVICES());
    }


    public function configureMenuItems(): iterable
    {

        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToRoute('Médiathèque', 'fa fa-picture-o', 'media.index');
        yield from $this->administratorMenuEntry();
        yield from $this->configMenuEntry();
        yield from $this->configRedirectEntry();

        yield MenuItem::section('easy.page.admin.menu.contents');
        yield MenuItem::linkToCrud('easy.page.admin.menu.pages', 'fa fa-file-alt', Page::class);
        yield MenuItem::linkToCrud('easy.block.admin.menu.shared_blocks', 'fa fa-file-alt', Block::class);
        yield MenuItem::linkToCrud('easy.menu.admin.menus', 'fa fa-file-alt', Menu::class);

        yield MenuItem::section('easy.blog.blog');
        yield MenuItem::linkToCrud('easy.blog.admin.menu.categories', 'fa fa-folder', Category::class);
        yield MenuItem::linkToCrud('easy.blog.admin.menu.articles', 'fa fa-file-alt', Post::class);

        yield MenuItem::section('easy.faq.faq');
        yield MenuItem::linkToCrud('easy.faq.admin.menu.categories', 'fa fa-folder', \App\Entity\Faq\Category::class);
        yield MenuItem::linkToCrud('easy.faq.admin.menu.entries', 'fa fa-file-alt', \App\Entity\Faq\Entry::class);

        // Shop Dashboard
        yield from $this->syliusItems();
    }
}
