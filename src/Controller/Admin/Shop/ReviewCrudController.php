<?php

namespace App\Controller\Admin\Shop;

use Adeliom\EasyFieldsBundle\Admin\Field\FormTypeField;
use App\Entity\Shop\Locale\Locale;
use App\Entity\Shop\Product\ProductReview;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\LocaleField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\RangeType;

class ReviewCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProductReview::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->showEntityActionsAsDropdown(false);
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('title', 'sylius.form.review.title')->setRequired(true);
        yield TextareaField::new('comment', 'sylius.form.review.comment')->setRequired(true);
        yield FormTypeField::new('rating', 'sylius.form.review.rating', RangeType::class)
            ->setFormTypeOption("attr", [
                'min' => 1,
                'max' => 5
            ])
            ->setRequired(true);
    }

}
