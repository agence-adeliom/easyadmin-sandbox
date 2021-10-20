<?php

namespace App\Controller\Admin\EasyPage;

use Adeliom\EasyCommonBundle\Enum\ThreeStateStatusEnum;
use Adeliom\EasyEditorBundle\Admin\Field\EasyEditorField;
use Adeliom\EasyFieldsBundle\Admin\Field\OembedField;
use Adeliom\EasyPageBundle\Controller\PageCrudController as BasePageCrudController;
use Adeliom\EasySeoBundle\Admin\Field\SEOField;
use App\Entity\EasyPage\Page;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Factory\AdminContextFactory;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;

class PageCrudController extends BasePageCrudController
{

    public static function getEntityFqcn(): string
    {
        return Page::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions = parent::configureActions($actions);
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->addFormTheme('@EasyEditor/form/editor_widget.html.twig')
            ->addFormTheme('@EasyFields/form/oembed_widget.html.twig')
            ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        $filters->add(ChoiceFilter::new("state","Status")->setChoices(ThreeStateStatusEnum::toArray()));

        return $filters;
    }


    public function informationsFields(string $pageName, $subject): iterable
    {
        yield from parent::informationsFields($pageName, $subject);
        yield OembedField::new('embed')
            ->setRequired(false)
            ->setColumns(12);
        yield EasyEditorField::new('content')
            ->setRequired(true)
            ->allowAdd(true)
            ->allowDrag(true)
            ->allowDelete(true)
            ->setColumns(12);
    }

}
