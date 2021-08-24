<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\OrderBundle\Admin;

use Adeliom\EasyShop\AdminBundle\Admin\AbstractAdmin;
use Adeliom\EasyShop\AdminBundle\Datagrid\ListMapper;
use Adeliom\EasyShop\AdminBundle\Form\FormMapper;
use Adeliom\EasyShop\Component\Currency\CurrencyDetectorInterface;
use Adeliom\EasyShop\Component\Currency\CurrencyFormType;
use Adeliom\EasyShop\Component\Product\Pool;
use Adeliom\EasyShop\OrderBundle\Form\Type\OrderStatusType;
use Adeliom\EasyShop\ProductBundle\Form\Type\ProductDeliveryStatusType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class OrderElementAdmin extends AbstractAdmin
{
    /**
     * @var CurrencyDetectorInterface
     */
    protected $currencyDetector;

    /**
     * @var Pool
     */
    protected $productPool;

    public function setCurrencyDetector(CurrencyDetectorInterface $currencyDetector): void
    {
        $this->currencyDetector = $currencyDetector;
    }

    public function setProductPool(Pool $productPool): void
    {
        $this->productPool = $productPool;
    }

    public function configure(): void
    {
        $this->parentAssociationMapping = 'order';
        $this->setTranslationDomain('SonataOrderBundle');
    }

    public function configureFormFields(FormMapper $formMapper): void
    {
        $productTypeOptions = [
            'choices' => array_flip(array_keys($this->productPool->getProducts())),
        ];

        $formMapper
            ->with('order_element.form.group_main_label')
                ->add('productType', ChoiceType::class, $productTypeOptions)
                ->add('quantity')
                ->add('price')
                ->add('vatRate')
                ->add('designation')
                ->add('description', null, ['required' => false])
                ->add('status', OrderStatusType::class, ['translation_domain' => 'SonataOrderBundle'])
                ->add('deliveryStatus', ProductDeliveryStatusType::class, ['translation_domain' => 'SonataDeliveryBundle'])
            ->end();
    }

    public function configureListFields(ListMapper $list): void
    {
        $list->addIdentifier('id');

        if (!$list->getAdmin()->isChild()) {
            $list->add('order');
        }

        $list->add('productType')
            ->add('getStatusName', 'trans', ['name' => 'status', 'catalogue' => 'SonataOrderBundle', 'sortable' => 'status'])
            ->add('getDeliveryStatusName', 'trans', ['name' => 'deliveryStatus', 'catalogue' => 'SonataOrderBundle', 'sortable' => 'deliveryStatus'])
            ->add('getTotalWithVat', CurrencyFormType::class, [
                'currency' => $this->currencyDetector->getCurrency()->getLabel(),
            ])
            ->add('getTotal', CurrencyFormType::class, [
                'currency' => $this->currencyDetector->getCurrency()->getLabel(),
            ]);
    }
}
