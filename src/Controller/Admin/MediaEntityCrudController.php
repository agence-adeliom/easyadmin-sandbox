<?php

namespace App\Controller\Admin;

use Adeliom\EasyMediaBundle\Admin\Field\EasyMediaField;
use App\Entity\MediaEntity;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CodeEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MediaEntityCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MediaEntity::class;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
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
            EasyMediaField::new('file'),
        ];
    }

}
