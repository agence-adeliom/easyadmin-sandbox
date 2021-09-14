<?php

namespace Adeliom\EasyShopBundle\Admin\Products;

use Adeliom\EasyFieldsBundle\Admin\Field\TranslationField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\TextType;

abstract class ProductAssociationTypeCrudController extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->addFormTheme('@EasyShop/SyliusFormTheme.html.twig')
            ->addFormTheme('@EasyFields/form/translations_widget.html.twig')
            ->setPageTitle(Crud::PAGE_INDEX, "sylius.ui.manage_association_types_of_your_products")
            ->setPageTitle(Crud::PAGE_NEW, "sylius.ui.create_product_association_type")
            ->setPageTitle(Crud::PAGE_EDIT, "sylius.ui.edit_association_type")
            ->setPageTitle(Crud::PAGE_DETAIL, "sylius.ui.association")
            ->setEntityLabelInSingular('sylius.ui.association')
            ->setEntityLabelInPlural('sylius.ui.associations')
            ->setFormOptions([
                'validation_groups' => ['Default', 'sylius']
            ])
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        $fieldsConfig = [
            'name' => [
                'field_type' => TextType::class,
                'required' => true,
                'label' => 'sylius.form.product_association_type.name'
            ]
        ];

        yield TextField::new('code', 'sylius.ui.code')
            ->setFormTypeOption('disabled', (in_array($pageName, [Crud::PAGE_EDIT]) ? 'disabled' : ''))
            ->setColumns(12);

        yield FormField::addPanel('sylius.form.attribute.translations');
        yield TranslationField::new("translations", false, $fieldsConfig);
    }
}
