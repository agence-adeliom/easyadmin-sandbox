<?php

namespace App\Controller\Admin\Shop;

use Adeliom\EasyFieldsBundle\Admin\Field\TranslationField;
use App\Entity\Shop\Product\ProductAssociationType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProductAssociationTypeCrudController extends AbstractCrudController
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
        return ProductAssociationType::class;
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->addFormTheme('@EasyShop/SyliusFormTheme.html.twig')
            ->addFormTheme('@EasyFields/form/translations_widget.html.twig')
            ->setPageTitle(Crud::PAGE_INDEX, "sylius.ui.manage_association_types_of_your_products")
            ->setPageTitle(Crud::PAGE_NEW, "sylius.ui.create_product_association_type")
            ->setPageTitle(Crud::PAGE_EDIT, "sylius.ui.edit_association_type")
            ->setPageTitle(Crud::PAGE_DETAIL, "sylius.ui.association")
            ->setEntityLabelInSingular('sylius.ui.association')
            ->setEntityLabelInPlural('sylius.ui.associations')
            ->setFormOptions([
                'validation_groups' => ['Default', 'sylius']
            ])
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        $context = $this->get(AdminContextProvider::class)->getContext();
        $subject = $context->getEntity();

        $fieldsConfig = [
            'name' => [
                'field_type' => TextType::class,
                'required' => true,
                'label' => 'sylius.form.product_association_type.name'
            ]
        ];

        yield TextField::new('code', 'sylius.ui.code')
            ->setFormTypeOption('disabled', (in_array($pageName, [Crud::PAGE_EDIT]) ? 'disabled' : ''))
            ->setColumns(12);

        yield FormField::addPanel('sylius.form.attribute.translations');
        yield TranslationField::new("translations", false, $fieldsConfig);
    }

}
