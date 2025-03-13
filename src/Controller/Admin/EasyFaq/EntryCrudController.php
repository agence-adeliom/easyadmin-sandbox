<?php

namespace App\Controller\Admin\EasyFaq;

use Adeliom\EasyCommonBundle\Enum\ThreeStateStatusEnum;
use Adeliom\EasyFaqBundle\Controller\EntryCrudController as BaseEntryCrudController;
use Adeliom\EasyGutenbergBundle\Admin\Field\GutenbergField;
use App\Entity\EasyFaq\Entry;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;

class EntryCrudController extends BaseEntryCrudController
{
    public static function getEntityFqcn(): string
    {
        return Entry::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->addFormTheme('@EasyGutenberg/form/gutenberg_widget.html.twig')
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions = parent::configureActions($actions);

        return $actions;
    }

    public function configureFilters(Filters $filters): Filters
    {
        $filters->add(ChoiceFilter::new('state', 'Status')->setChoices(ThreeStateStatusEnum::cases()));

        return $filters;
    }

    public function informationsFields(string $pageName, $subject): iterable
    {
        yield FormField::addPanel('easy.faq.admin.panel.information')->addCssClass('col-12');
        yield TextField::new('name', 'easy.faq.admin.field.question')
            ->setRequired(true)
            ->setColumns(12);

        yield GutenbergField::new('answer', 'easy.faq.admin.field.answer')
            ->setRequired(true)
            ->setColumns(12);
    }
}
