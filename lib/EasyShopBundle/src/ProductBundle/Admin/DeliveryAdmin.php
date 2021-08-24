<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\ProductBundle\Admin;

use Knp\Menu\ItemInterface as MenuItemInterface;
use Adeliom\EasyShop\AdminBundle\Admin\AbstractAdmin;
use Adeliom\EasyShop\AdminBundle\Admin\AdminInterface;
use Adeliom\EasyShop\AdminBundle\Datagrid\DatagridMapper;
use Adeliom\EasyShop\AdminBundle\Datagrid\ListMapper;
use Adeliom\EasyShop\AdminBundle\Form\FormMapper;
use Adeliom\EasyShop\AdminBundle\Form\Type\ModelListType;
use Adeliom\EasyShop\Component\Form\Type\DeliveryChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class DeliveryAdmin extends AbstractAdmin
{
    protected $parentAssociationMapping = 'product';

    public function configure(): void
    {
        $this->setTranslationDomain('SonataProductBundle');
    }

    public function configureFormFields(FormMapper $formMapper): void
    {
        if (!$this->isChild()) {
            $formMapper->add('product', ModelListType::class, [], [
                'admin_code' => 'easy_shop.product.admin.product',
            ]);
        }

        $formMapper
            ->add('enabled')
            ->add('code', DeliveryChoiceType::class)
            ->add('perItem')
            ->add('countryCode', CountryType::class)
            ->add('zone');
    }

    public function configureListFields(ListMapper $list): void
    {
        if (!$this->isChild()) {
            $list
                ->addIdentifier('id')
                ->addIdentifier('product', null, [
                    'admin_code' => 'easy_shop.product.admin.product',
                ]);
        }

        $list
            ->addIdentifier('code')
            ->add('enabled')
            ->add('perItem')
            ->add('countryCode')
            ->add('zone');
    }

    protected function configureSideMenu(MenuItemInterface $menu, $action, ?AdminInterface $childAdmin = null): void
    {
        if (!$childAdmin && !\in_array($action, ['edit'], true)) {
            return;
        }

        $admin = $this->isChild() ? $this->getParent() : $this;

        $id = $admin->getRequest()->get('id');

        $menu->addChild(
            'product.sidemenu.link_product_edit',
            ['uri' => $admin->generateUrl('edit', ['id' => $id])]
        );
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('code')
            ->add('countryCode');
    }
}
