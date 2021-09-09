<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Block;
use App\Entity\Category;
use App\Entity\MediaEntity;
use App\Entity\Menu\Menu;
use App\Entity\Page;
use App\Entity\Post;
use App\Entity\Shop\Addressing\Country;
use App\Entity\Shop\Addressing\Zone;
use App\Entity\Shop\Channel\Channel;
use App\Entity\Shop\Currency\Currency;
use App\Entity\Shop\Currency\ExchangeRate;
use App\Entity\Shop\Customer\Customer;
use App\Entity\Shop\Customer\CustomerGroup;
use App\Entity\Shop\Locale\Locale;
use App\Entity\Shop\Order\Order;
use App\Entity\Shop\Payment\Payment;
use App\Entity\Shop\Payment\PaymentMethod;
use App\Entity\Shop\Product\Product;
use App\Entity\Shop\Product\ProductAssociationType;
use App\Entity\Shop\Product\ProductAttribute;
use App\Entity\Shop\Product\ProductOption;
use App\Entity\Shop\Product\ProductReview;
use App\Entity\Shop\Promotion\Promotion;
use App\Entity\Shop\Shipping\Shipment;
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
use Symfony\Component\Translation\TranslatorInterface;

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
            ->setTitle('My Project Name')
            ;
    }



    public function configureMenuItems(): iterable
    {

        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToRoute('Médiathèque', 'fa fa-picture-o', 'media.index');

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

        yield MenuItem::section('sylius.ui.products');
        yield MenuItem::linkToCrud('sylius.ui.taxons', 'fas fa-folder', Taxon::class);
        yield MenuItem::linkToCrud('sylius.ui.items', 'fas fa-cube', Product::class);
        yield MenuItem::linkToCrud('sylius.ui.inventory', 'fas fa-history', Product::class)->setAction("manageStock");
        yield MenuItem::linkToCrud('sylius.ui.attributes', 'fas fa-cubes', ProductAttribute::class);
        yield MenuItem::linkToCrud('sylius.ui.options', 'fas fa-sliders-h', ProductOption::class);
        yield MenuItem::linkToCrud('sylius.ui.association_types', 'fas fa-tasks', ProductAssociationType::class);

        yield MenuItem::section('sylius.ui.marketing');
        yield MenuItem::linkToCrud('sylius.ui.promotions', 'fas fa-percentage', Promotion::class);
        yield MenuItem::linkToCrud('sylius.ui.product_reviews', 'fas fa-newspaper', ProductReview::class);

        yield MenuItem::section('sylius.ui.sales');
        yield MenuItem::linkToCrud('sylius.ui.orders', 'fas fa-shopping-cart', Order::class);
        yield MenuItem::linkToCrud('sylius.ui.payments', 'fas fa-credit-card', Payment::class);
        yield MenuItem::linkToCrud('sylius.ui.shipments', 'fas fa-truck', Shipment::class);
        yield MenuItem::linkToCrud('sylius.ui.orders_statistics', 'far fa-chart-bar', Order::class)->setAction("statistics");

        yield MenuItem::section('sylius.ui.customer');
        yield MenuItem::linkToCrud('sylius.ui.customers', 'fas fa-users', Customer::class);
        yield MenuItem::linkToCrud('sylius.form.user.groups', 'fas fa-archive', CustomerGroup::class);

        yield MenuItem::section('sylius.ui.configuration');
        yield MenuItem::linkToCrud('sylius.ui.channels', 'fas fa-random', Channel::class);
        yield MenuItem::linkToCrud('sylius.ui.countries', 'fas fa-flag', Country::class);
        yield MenuItem::linkToCrud('sylius.ui.zones', 'fas fa-globe', Zone::class);
        yield MenuItem::linkToCrud('sylius.ui.currencies', 'fas fa-dollar-sign', Currency::class);
        yield MenuItem::linkToCrud('sylius.ui.exchange_rates', 'fas fa-exchange-alt', ExchangeRate::class);
        yield MenuItem::linkToCrud('sylius.ui.locales', 'fas fa-language', Locale::class);
        yield MenuItem::linkToCrud('sylius.ui.payment_methods', 'fas fa-credit-card', PaymentMethod::class);
        yield MenuItem::linkToCrud('sylius.ui.shipping_methods', 'fas fa-truck', ShippingMethod::class);
        yield MenuItem::linkToCrud('sylius.ui.shipping_categories', 'fas fa-th-list', ShippingCategory::class);
        yield MenuItem::linkToCrud('sylius.ui.tax_categories', 'fas fa-tags', TaxCategory::class);
        yield MenuItem::linkToCrud('sylius.ui.tax_rates', 'fas fa-money-bill', TaxRate::class);


        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
