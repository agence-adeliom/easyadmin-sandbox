<?php

namespace App\Controller\Admin;

use Adeliom\EasyFieldsBundle\Admin\Field\IconField;
use Adeliom\EasyFieldsBundle\Admin\Field\SortableCollectionField;
use Adeliom\EasyMediaBundle\Admin\Field\EasyMediaField;
use App\Entity\MediaEntity;
use App\Form\Type\DataType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

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
            ->addFormTheme('@EasyFields/form/form_theme.html.twig')
            ->addFormTheme('@EasyFields/form/icon_widget.html.twig')
            ->addFormTheme('@EasyFields/form/sortable_widget.html.twig')

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
                'restrictions_uploadTypes' => ['image/*'],
                'editor' => false,
                'upload' => false,
                'bulk_selection' => false,
                'move' => false,
                'rename' => false,
                'metas' => false,
                'delete' => false,
            ]),

             SortableCollectionField::new('data')
                ->setEntryType(DataType::class)
                ->hideOnIndex()
                ->onlyOnForms(),
//            EasyMediaField::new('text')->setFormTypeOptions([
//                "restrictions_uploadTypes" => ["image/*"],
//                "editor" => false,
//                "upload" => false,
//                "bulk_selection" => false,
//                "move" => false,
//                "rename" => false,
//                "metas" => false,
//                "delete" => false
//            ]),
            IconField::new('text')
                ->setSelectButtonLabel('Choisir une icône')
                ->setHelp('Choisir une icône')
                ->setFonts([
                    'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.0/font/bootstrap-icons.css',
                ])
                ->setJsonUrl('/test.json'),

            IconField::new('icon')
                ->setSelectButtonLabel('Choisir une icône 2')
                ->setHelp('Choisir une icône')
                ->setDeleteLabel('Custom delete label')
                ->setFonts([
                    'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.0/font/bootstrap-icons.css',
                ])
                ->setJsonUrl('/test.json'),
        ];
    }
}
