<?php

namespace App\Controller\Admin\Shop;

use Adeliom\EasyFieldsBundle\Admin\Field\AssociationField;
use Adeliom\EasyFieldsBundle\Admin\Field\FormTypeField;
use Adeliom\EasyFieldsBundle\Admin\Field\TranslationField;
use App\Entity\Shop\Product\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sylius\Bundle\AttributeBundle\Form\Type\AttributeChoiceType;
use Sylius\Bundle\AttributeBundle\Form\Type\AttributeTypeChoiceType;
use Sylius\Bundle\ProductBundle\Form\Type\ProductAssociationsType;
use Sylius\Bundle\ProductBundle\Form\Type\ProductAttributeType;
use Sylius\Bundle\ProductBundle\Form\Type\ProductAttributeValueType;
use Sylius\Bundle\ProductBundle\Form\Type\ProductTranslationType;
use Sylius\Bundle\ProductBundle\Form\Type\ProductType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig')
            ->addFormTheme('@EasyEditor/form/editor_widget.html.twig')
            ->addFormTheme('@EasyFields/form/association_widget.html.twig')
            ->addFormTheme('@EasyFields/form/sortable_widget.html.twig')
            ->addFormTheme('@EasyFields/form/choice_mask_widget.html.twig')
            ->addFormTheme('@EasyFields/form/translations_widget.html.twig')
            ->addFormTheme('@EasyMedia/form/easy-media.html.twig')
            //->addFormTheme('@SyliusUi/Form/theme.html.twig')
            ;
    }


    public function configureFields(string $pageName): iterable
    {
        $fieldsConfig = [
            'name' => [
                'field_type' => TextType::class,
                'required' => true,
            ],
            'slug' => [
                'field_type' => TextType::class,
                'required' => true,
            ],
            'description' => [
                'field_type' => CKEditorType::class,
                'required' => true,
            ],
            'shortDescription' => [
                'field_type' => TextareaType::class,
                'required' => true,
            ],
            'metaKeywords' => [
                'field_type' => TextType::class,
                'required' => true,
            ],
            'metaDescription' => [
                'field_type' => TextareaType::class,
                'required' => true,
            ],
        ];

        $p = new Product();


        yield TextField::new('code');
        yield BooleanField::new('enabled');

        yield FormField::addPanel("Taxonomy")->collapsible()->renderCollapsed();
        yield AssociationField::new('mainTaxon')->autocomplete()->listSelector()->listDisplayColumns([1,2])->setCrudController(TaxonCrudController::class);
        yield AssociationField::new('productTaxons')->autocomplete()->listSelector()->listDisplayColumns([1,2])->setCrudController(TaxonCrudController::class);

        yield FormField::addPanel("Attributes")->collapsible()->renderCollapsed();
        yield CollectionField::new("attributes", false)->setFormTypeOptions([
            'entry_type' => ProductAttributeValueType::class,
            'required' => false,
            'prototype' => true,
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'label' => false,
        ]);
        //yield FormTypeField::new("associations", false, ProductAssociationsType::class);
        yield FormField::addPanel("Contenus")->collapsible()->renderCollapsed();
        yield TranslationField::new("translations", 'Contenus', $fieldsConfig);

    }
}
