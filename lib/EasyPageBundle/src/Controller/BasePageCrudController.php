<?php

namespace Adeliom\EasyPageBundle\Controller;


use Adeliom\EasyFieldsBundle\Admin\Field\EnumField;
use Adeliom\EasyCommonBundle\Enum\ThreeStateStatusEnum;
use Adeliom\EasySeoBundle\Admin\Field\SEOField;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class BasePageCrudController extends AbstractCrudController
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
        //yield from $this->seoFields($pageName, $subject);
        yield from $this->publishFields($pageName, $subject);
    }

    public function informationsFields(string $pageName, $subject): iterable
    {
        yield FormField::addPanel($this->translator->trans("admin.panel.information", [], "EasyPageBundle"))->addCssClass("col-8");
        yield TextField::new('name', $this->translator->trans("admin.field.name", [], "EasyPageBundle"))
            ->setRequired(true)
            ->setColumns(12);
    }

    public function metadataFields(string $pageName, $subject): iterable
    {
        yield FormField::addPanel($this->translator->trans("admin.panel.metadatas", [], "EasyPageBundle"))->collapsible()->addCssClass("col-4");
        yield SlugField::new('slug', $this->translator->trans("admin.field.slug", [], "EasyPageBundle"))
            ->setRequired(true)
            ->hideOnIndex()
            ->setTargetFieldName('name')
            ->setUnlockConfirmationMessage($this->translator->trans("admin.field.slug_edit", [], "EasyPageBundle"))
            ->setColumns(12);


        yield TextField::new('action', $this->translator->trans("admin.field.action", [], "EasyPageBundle"))
            ->hideOnIndex()
            ->setHelp($this->translator->trans("admin.field.action_help", [], "EasyPageBundle"))
            ->setColumns(12);

        yield AssociationField::new("parent", $this->translator->trans("admin.field.parent", [], "EasyPageBundle"))
            ->setQueryBuilder(function (QueryBuilder $queryBuilder) use ($subject) {
                $rootAllias = $queryBuilder->getAllAliases()[0];
                if($subject->getPrimaryKeyValue()){
                    $queryBuilder->andWhere(sprintf("%s.id != :currentID", $rootAllias))
                        ->setParameter("currentID", $subject->getPrimaryKeyValue());
                }
                return $queryBuilder;
            })
            ->setColumns(12);
    }

    public function seoFields(string $pageName, $subject): iterable
    {
        yield FormField::addPanel($this->translator->trans("admin.panel.seo", [], "EasyPageBundle"))->collapsible()->addCssClass("col-4");
        yield SEOField::new("seo");
    }

    public function publishFields(string $pageName, $subject): iterable
    {
        yield FormField::addPanel($this->translator->trans("admin.panel.publication", [], "EasyPageBundle"))->collapsible()->addCssClass("col-4");
        yield EnumField::new("state", $this->translator->trans("admin.field.state", [], "EasyPageBundle"))
            ->setEnum(ThreeStateStatusEnum::class)
            ->setRequired(true)
            ->renderExpanded(true)
            ->renderAsBadges(true);
        yield DateTimeField::new('publishDate', $this->translator->trans("admin.field.publishDate", [], "EasyPageBundle"))->setFormat('Y-MM-dd HH:mm')
            ->setRequired(true)
            ->hideOnIndex()
            ->setColumns(6);
        yield DateTimeField::new('unpublishDate', $this->translator->trans("admin.field.unpublishDate", [], "EasyPageBundle"))->setFormat('Y-MM-dd HH:mm')
            ->setRequired(false)
            ->hideOnIndex()
            ->setColumns(6);
    }
}
