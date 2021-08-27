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
            ]
        ];

        yield TextField::new('code', 'sylius.ui.code')->setColumns(12);

        yield FormField::addPanel('sylius.form.attribute.translations');
        yield TranslationField::new("translations", false, $fieldsConfig);
    }

}
