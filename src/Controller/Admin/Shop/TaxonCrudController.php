<?php

namespace App\Controller\Admin\Shop;

use Adeliom\EasyFieldsBundle\Admin\Field\AssociationField;
use Adeliom\EasyFieldsBundle\Admin\Field\TranslationField;
use App\Entity\Shop\Taxonomy\Taxon;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TaxonCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Taxon::class;
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
            ->setPageTitle(Crud::PAGE_INDEX, "sylius.ui.manage_taxons")
            ->setPageTitle(Crud::PAGE_NEW, "sylius.ui.new_taxon")
            ->setPageTitle(Crud::PAGE_EDIT, "sylius.ui.edit_taxon")
            ->setPageTitle(Crud::PAGE_DETAIL, "sylius.ui.taxonomy")
            ->setEntityLabelInSingular('sylius.ui.taxonomy')
            ->setEntityLabelInPlural('sylius.ui.taxons')

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
                'label' => 'sylius.form.taxon.name',
                'required' => true,
            ],
            'slug' => [
                'field_type' => TextType::class,
                'label' => 'sylius.form.taxon.slug',
                'required' => false,
            ],
            'description' => [
                'field_type' => TextareaType::class,
                'label' => 'sylius.form.taxon.description',
                'required' => false,
            ]
        ];

        yield TextField::new('code','sylius.ui.code');
        yield AssociationField::new('parent', 'sylius.form.taxon.parent')->autocomplete()->listSelector()->listDisplayColumns([1, 2])->setCrudController(TaxonCrudController::class);
        yield BooleanField::new('enabled', 'sylius.form.taxon.enabled')->renderAsSwitch(in_array($pageName, [Crud::PAGE_NEW, Crud::PAGE_EDIT]));
        yield FormField::addPanel('sylius.form.taxon.name')->collapsible();
        yield TranslationField::new("translations", false, $fieldsConfig);
    }

}
