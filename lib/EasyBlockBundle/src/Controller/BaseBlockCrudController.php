<?php

namespace Adeliom\EasyBlockBundle\Controller;

use Adeliom\EasyBlockBundle\Admin\Field\BlockSettingsField;
use Adeliom\EasyBlockBundle\Block\BlockCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class BaseBlockCrudController extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->addFormTheme('@EasyCommon/crud/custom_panel.html.twig')
            ;
    }

    public static function getSubscribedServices()
    {
        return array_merge(parent::getSubscribedServices(), [
            'easy_block.block_collection' => '?'.BlockCollection::class,
            'translator' => '?'.TranslatorInterface::class
        ]);
    }

    public function new(AdminContext $context)
    {
        global $blockType;
        $blockType = $context->getRequest()->query->get('block_type');
        if(!$blockType){
            $blockCollection = $this->container->get("easy_block.block_collection");
            return $this->render("@EasyBlock/crud/choose_block.html.twig", [
                "blocks" => $blockCollection->getBlocks()
            ]);
        }

        return parent::new($context);
    }

    public function createEntity(string $entityFqcn)
    {
        global $blockType;
        $entity = new $entityFqcn();
        $entity->setType($blockType);
        $entity->setStatus(true);
        $entity->setSettings(call_user_func([$blockType, 'getDefaultSettings']));

        return $entity;
    }


    public function configureFields(string $pageName): iterable
    {
        global $blockType;
        $translator = $this->container->get('translator');

        $context = $this->get(AdminContextProvider::class)->getContext();
        $subject = $context->getEntity();

        if($subject->getInstance() && $subject->getInstance()->getType()){
            $blockType = $subject->getInstance()->getType();
        }

        yield TextField::new('name', $translator->trans("admin.label.name", [], "EasyBlockBundle"))->setRequired(true)->setColumns(8);
        yield TextField::new('type', $translator->trans("admin.label.type", [], "EasyBlockBundle"))->setRequired(true)->setColumns(4)->setFormTypeOption('disabled','disabled');
        yield BooleanField::new('status', $translator->trans("admin.label.status", [], "EasyBlockBundle"))->setColumns(12);

        if($blockType && in_array($pageName, [Crud::PAGE_NEW, Crud::PAGE_EDIT])){
            yield FormField::addPanel($translator->trans("admin.label.settings_section", [], "EasyBlockBundle"));
            yield BlockSettingsField::new('settings', false)->setFormType($blockType);
        }
    }

}
