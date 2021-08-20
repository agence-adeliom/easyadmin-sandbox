<?php

namespace Adeliom\EasyBlogBundle\Controller;


use Adeliom\EasyCommonBundle\Enum\ThreeStateStatusEnum;
use Adeliom\EasyFieldsBundle\Admin\Field\AssociationField;
use Adeliom\EasySeoBundle\Admin\Field\SEOField;
use App\Controller\Admin\PostCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;

abstract class BasePostCrudController extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig')
            ->addFormTheme('@EasyFields/form/association_widget.html.twig')
            ->addFormTheme('@EasyCommon/crud/custom_panel.html.twig')
            ->addFormTheme('@EasyEditor/form/editor_widget.html.twig')
            ->addFormTheme('@EasyMedia/form/easy-media.html.twig')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        $context = $this->get(AdminContextProvider::class)->getContext();
        $subject = $context->getEntity();

        yield IdField::new('id')->hideOnForm();
        yield from $this->informationsFields($pageName, $subject);
        yield from $this->metadataFields($pageName, $subject);
        yield from $this->seoFields($pageName, $subject);
        yield from $this->publishFields($pageName, $subject);
    }

    public function informationsFields(string $pageName, $subject): iterable
    {
        yield FormField::addPanel('Informations de la page')->addCssClass("col-8");
        yield TextField::new('name')
            ->setRequired(true)
            ->setColumns(12);
    }

    public function metadataFields(string $pageName, $subject): iterable
    {
        yield FormField::addPanel('Métadonnées')->collapsible()->addCssClass("col-4");
        yield SlugField::new('slug')
            ->setRequired(true)
            ->hideOnIndex()
            ->setTargetFieldName('name')
            ->setUnlockConfirmationMessage("Are you sure ?")
            ->setColumns(12);
        yield AssociationField::new("category", "Catégorie")
            ->autocomplete()
            ->allowAdd()
            ->listSelector(true)
            ->setCrudController($this->getParameter("easy_blog.category.crud"))
        ;
    }

    public function seoFields(string $pageName, $subject): iterable
    {
        yield FormField::addPanel('SEO')->collapsible()->addCssClass("col-4");
        yield SEOField::new("seo");
    }

    public function publishFields(string $pageName, $subject): iterable
    {
        yield FormField::addPanel('Publication')->collapsible()->addCssClass("col-4");
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
