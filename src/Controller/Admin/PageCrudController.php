<?php

namespace App\Controller\Admin;

use Adeliom\EasyCommonBundle\Enum\ThreeStateStatusEnum;
use Adeliom\EasyEditorBundle\Admin\Field\EasyEditorField;
use Adeliom\EasySeoBundle\Admin\Field\SEOField;
use App\Entity\Page;
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

class PageCrudController extends AbstractCrudController
{


    public static function getEntityFqcn(): string
    {
        return Page::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig')
            ->addFormTheme('@EasyCommon/crud/custom_panel.html.twig')
            ->addFormTheme('@EasyEditor/form/editor_widget.html.twig')
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

    public function configureFilters(Filters $filters): Filters
    {
        $filters->add(ChoiceFilter::new("state","Status")->setChoices(ThreeStateStatusEnum::toArray()));

        return $filters;
    }

    public function configureFields(string $pageName): iterable
    {
        $context = $this->get(AdminContextProvider::class)->getContext();
        $subject = $context->getEntity();

        yield IdField::new('id')->hideOnForm();

        yield FormField::addPanel('Informations de la page')->collapsible()->addCssClass("col-8");
        yield TextField::new('name')
            ->setRequired(true)
            ->setColumns(12);

        yield FormField::addPanel('Métadonnées')->addCssClass("col-4");

        yield SlugField::new('slug')
            ->setRequired(true)
            ->hideOnIndex()
            ->setTargetFieldName('name')
            ->setUnlockConfirmationMessage("Are you sure ?")
            ->setColumns(12);


        yield TextField::new('action')
            ->hideOnIndex()
            ->setHelp("To apply change you have to clear symfony cache")
            ->setColumns(12);

        yield AssociationField::new("parent", "Page parente")
            ->setQueryBuilder(function (QueryBuilder $queryBuilder) use ($subject) {
                $rootAllias = $queryBuilder->getAllAliases()[0];
                if($subject->getPrimaryKeyValue()){
                    $queryBuilder->andWhere(sprintf("%s.id != :currentID", $rootAllias))
                        ->setParameter("currentID", $subject->getPrimaryKeyValue());
                }
                return $queryBuilder;
            })
            ->setColumns(12);


        yield FormField::addPanel('Contenu de la page')->collapsible()->addCssClass("col-8");
        yield EasyEditorField::new('content')
            ->setRequired(true)
            ->allowAdd(true)
            ->allowDrag(true)
            ->allowDelete(true)
            ->setColumns(12);

        yield FormField::addPanel('SEO')->addCssClass("col-4");
        yield SEOField::new("seo");

        yield FormField::addPanel('Publication')->addCssClass("col-4");
        yield ChoiceField::new("state", "Status")
            ->setChoices(ThreeStateStatusEnum::toArray())
            ->setRequired(true)
            ->renderExpanded(true)
            ->renderAsBadges(true);
        yield DateTimeField::new('publishDate', "Date de publication")->setFormat('Y-MM-dd HH:mm')
            ->setRequired(true)
            ->hideOnIndex()
            ->setColumns(6);
        yield DateTimeField::new('unpublishDate', "Date de dépublication")->setFormat('Y-MM-dd HH:mm')
            ->setRequired(false)
            ->hideOnIndex()
            ->setColumns(6);

    }

}
