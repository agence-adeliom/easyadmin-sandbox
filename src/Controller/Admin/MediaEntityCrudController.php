<?php

namespace App\Controller\Admin;

use Adeliom\EasyFieldsBundle\Admin\Field\AssociationField;
use Adeliom\EasyMediaBundle\Admin\Field\EasyMediaField;
use App\Entity\Article;
use App\Entity\MediaEntity;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\ComparisonType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class MediaEntityCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MediaEntity::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig')
            ->addFormTheme('@EasyMedia/form/easy-media.html.twig')
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions = parent::configureActions($actions);
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            EasyMediaField::new('file')->setFormTypeOptions([
                "restrictions_uploadTypes" => ["image/*"],
                "editor" => false,
                "upload" => false,
                "bulk_selection" => false,
                "move" => false,
                "rename" => false,
                "metas" => false,
                "delete" => false
            ]),
            EasyMediaField::new('text')->setFormTypeOptions([
                "restrictions_uploadTypes" => ["image/*"],
                "editor" => false,
                "upload" => false,
                "bulk_selection" => false,
                "move" => false,
                "rename" => false,
                "metas" => false,
                "delete" => false
            ]),
        ];
    }

}
