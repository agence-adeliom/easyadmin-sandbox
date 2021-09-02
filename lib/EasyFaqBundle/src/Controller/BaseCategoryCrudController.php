<?php

namespace Adeliom\EasyFaqBundle\Controller;


use Adeliom\EasyCommonBundle\Enum\ThreeStateStatusEnum;
use Adeliom\EasyFieldsBundle\Admin\Field\AssociationField;
use Adeliom\EasySeoBundle\Admin\Field\SEOField;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class BaseCategoryCrudController extends AbstractCrudController
{
    protected $translator;

    public static function getSubscribedServices()
    {
        return array_merge(parent::getSubscribedServices(), [
            'translator' => '?'.TranslatorInterface::class
        ]);
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

    public function configureFields(string $pageName): iterable
    {
        $context = $this->get(AdminContextProvider::class)->getContext();
        $subject = $context->getEntity();
        $this->translator = $this->container->get('translator');

        yield IdField::new('id')->hideOnForm();
        yield from $this->informationsFields($pageName, $subject);
        yield from $this->metadataFields($pageName, $subject);
        yield from $this->seoFields($pageName, $subject);
        yield from $this->publishFields($pageName, $subject);
    }

    public function informationsFields(string $pageName, $subject): iterable
    {
        yield FormField::addPanel($this->translator->trans("admin.panel.information", [], "EasyFaqBundle"))->addCssClass("col-8");
        yield TextField::new('name', $this->translator->trans("admin.field.name", [], "EasyFaqBundle"))
            ->setRequired(true)
            ->setColumns(12);
    }

    public function metadataFields(string $pageName, $subject): iterable
    {
        yield FormField::addPanel($this->translator->trans("admin.panel.metadatas", [], "EasyFaqBundle"))->collapsible()->addCssClass("col-4");
        yield SlugField::new('slug', $this->translator->trans("admin.field.slug", [], "EasyFaqBundle"))
            ->setRequired(true)
            ->hideOnIndex()
            ->setTargetFieldName('name')
            ->setUnlockConfirmationMessage($this->translator->trans("admin.field.slug_edit", [], "EasyFaqBundle"))
            ->setColumns(12);
    }

    public function seoFields(string $pageName, $subject): iterable
    {
        yield FormField::addPanel($this->translator->trans("admin.panel.seo", [], "EasyFaqBundle"))->collapsible()->addCssClass("col-4");
        yield SEOField::new("seo");
    }

    public function publishFields(string $pageName, $subject): iterable
    {
        yield FormField::addPanel($this->translator->trans("admin.panel.publication", [], "EasyFaqBundle"))->collapsible()->addCssClass("col-4");
        yield BooleanField::new("status", $this->translator->trans("admin.field.state", [], "EasyFaqBundle"))
            ->setRequired(true)
            ->renderAsSwitch(true);
    }
}
