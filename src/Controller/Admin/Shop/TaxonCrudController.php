<?php

namespace App\Controller\Admin\Shop;

use Adeliom\EasyFieldsBundle\Admin\Field\AssociationField;
use Adeliom\EasyFieldsBundle\Admin\Field\TranslationField;
use App\Entity\Shop\Product\Product;
use App\Entity\Shop\Taxonomy\Taxon;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
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
            ]
        ];

        yield TextField::new('code');
        yield AssociationField::new('parent')->autocomplete()->listSelector()->listDisplayColumns([1,2])->setCrudController(TaxonCrudController::class);
        yield BooleanField::new('enabled');
        yield FormField::addPanel("Contenus")->collapsible();
        yield TranslationField::new("translations", 'Contenus', $fieldsConfig);
    }

}
