<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Block;
use App\Entity\Category;
use App\Entity\MediaEntity;
use App\Entity\Page;
use App\Entity\Post;
use App\Entity\Shop\Addressing\Country;
use App\Entity\Shop\Addressing\Zone;
use App\Entity\Shop\Channel\Channel;
use App\Entity\Shop\Currency\Currency;
use App\Entity\Shop\Currency\ExchangeRate;
use App\Entity\Shop\Locale\Locale;
use App\Entity\Shop\Product\Product;
use App\Entity\Shop\Shipping\ShippingCategory;
use App\Entity\Shop\Shipping\ShippingMethod;
use App\Entity\Shop\Taxation\TaxCategory;
use App\Entity\Shop\Taxation\TaxRate;
use App\Entity\Shop\Taxonomy\Taxon;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
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
            ->setTitle('My Project Name');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToRoute('Médiathèque', 'fa fa-picture-o', 'media.index');
        yield MenuItem::section('Contenu');

        yield MenuItem::linkToCrud('Medias', 'fa fa-picture-o', MediaEntity::class);
        yield MenuItem::linkToCrud('Article', 'fa fa-file-alt', Article::class);
        yield MenuItem::linkToCrud('Page', 'fa fa-file-alt', Page::class);
        yield MenuItem::section('Blog');
        yield MenuItem::linkToCrud('Catégorie', 'fa fa-file-alt', Category::class);
        yield MenuItem::linkToCrud('Post', 'fa fa-file-alt', Post::class);
        yield MenuItem::section('Settings');
        yield MenuItem::linkToCrud('Blocks', 'fa fa-file-alt', Block::class);
        yield MenuItem::section('Catalog');
        yield MenuItem::linkToCrud('Taxon', 'fa fa-file-alt', Taxon::class);
        yield MenuItem::linkToCrud('Product', 'fa fa-file-alt', Product::class);

        yield MenuItem::section('Shop Settings');
        yield MenuItem::linkToCrud('Channel', 'fa fa-file-alt', Channel::class);
        yield MenuItem::linkToCrud('Countries', 'fa fa-file-alt', Country::class);
        yield MenuItem::linkToCrud('Zones', 'fa fa-file-alt', Zone::class);
        yield MenuItem::linkToCrud('Currency', 'fa fa-file-alt', Currency::class);
        yield MenuItem::linkToCrud('ExchangeRate', 'fa fa-file-alt', ExchangeRate::class);
        yield MenuItem::linkToCrud('Locales', 'fa fa-file-alt', Locale::class);
        yield MenuItem::linkToCrud('Shipping methods', 'fa fa-file-alt', ShippingMethod::class);
        yield MenuItem::linkToCrud('Shipping categories', 'fa fa-file-alt', ShippingCategory::class);
        yield MenuItem::linkToCrud('Tax categories', 'fa fa-file-alt', TaxCategory::class);
        yield MenuItem::linkToCrud('Tax rate', 'fa fa-file-alt', TaxRate::class);
        
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
