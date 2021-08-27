<?php

namespace App\Controller\Admin\Shop;

use Adeliom\EasyFieldsBundle\Admin\Field\TranslationField;
use App\Entity\Shop\Product\ProductOption;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Sylius\Bundle\ProductBundle\Form\Type\ProductOptionValueType;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProductOptionCrudController extends AbstractCrudController
{
    private AdminUrlGenerator $crudUrlGenerator;
    private ParameterBagInterface $parameterBag;


    public function __construct(AdminUrlGenerator $crudUrlGenerator, ParameterBagInterface $parameterBag)
    {
        $this->crudUrlGenerator = $crudUrlGenerator;
        $this->parameterBag = $parameterBag;
    }

    public static function getEntityFqcn(): string
    {
        return ProductOption::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->addFormTheme('@EasyShop/SyliusFormTheme.html.twig')
            ->addFormTheme('@EasyFields/form/translations_widget.html.twig');
    }

    public function configureFields(string $pageName): iterable
    {
        $context = $this->get(AdminContextProvider::class)->getContext();
        $subject = $context->getEntity();

        $fieldsConfig = [
            'name' => [
                'field_type' => TextType::class,
                'required' => true,
                'label' => 'sylius.form.option.name'
            ]
        ];

        yield TextField::new('code', 'sylius.ui.code')->setColumns(6);
        yield IntegerField::new('position', 'sylius.form.option.position')
            ->setRequired(false)
            ->setColumns(6);

        yield FormField::addPanel('sylius.form.option.name');
        yield TranslationField::new("translations", false, $fieldsConfig);

        yield FormField::addPanel('sylius.form.option.values');
        yield CollectionField::new("values", false)
            ->setEntryType(ProductOptionValueType::class)
            ->allowAdd()
            ->setFormTypeOption("by_reference", false)
            ->setFormTypeOption("label", false)
            ->setFormTypeOption("button_add_label", 'sylius.form.option_value.add_value');
    }

}
