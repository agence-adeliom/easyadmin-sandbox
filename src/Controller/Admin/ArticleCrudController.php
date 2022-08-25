<?php

namespace App\Controller\Admin;

use Adeliom\EasyEditorBundle\Admin\Field\EasyEditorField;
use Adeliom\EasyFieldsBundle\Admin\Field\AssociationField;
use Adeliom\EasyFieldsBundle\Admin\Field\ChoiceMaskField;
use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig')
            ->addFormTheme('@EasyEditor/form/editor_widget.html.twig')
            ->addFormTheme('@EasyFields/form/association_widget.html.twig')
            ->addFormTheme('@EasyFields/form/sortable_widget.html.twig')
            ->addFormTheme('@EasyFields/form/choice_mask_widget.html.twig')
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
            IdField::new('id')->hideOnForm(),

            ChoiceMaskField::new('title')
            ->setChoices([
                "Contenu" => "content",
                "Media" => "media"
            ])
            ->setMap([
                "content" => ["content", "media"],
                "media" => ["media"]
            ]),

            EasyEditorField::new('content'),
            //SortableCollectionField::new('content')->allowAdd(true)->allowDrag(true),
            AssociationField::new('media', "Compagny")
                ->autocomplete()
                ->allowAdd()
                ->setCrudController(MediaEntityCrudController::class)
                ->listSelector(true)
        ];
    }
}
