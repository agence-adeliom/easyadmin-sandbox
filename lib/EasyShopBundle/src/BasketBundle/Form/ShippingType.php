<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\BasketBundle\Form;

use Adeliom\EasyShop\Component\Basket\BasketInterface;
use Adeliom\EasyShop\Component\Delivery\Pool as DeliveryPool;
use Adeliom\EasyShop\Component\Delivery\ServiceDeliverySelectorInterface;
use Adeliom\EasyShop\Component\Delivery\UndeliverableCountryException;
use Adeliom\EasyShop\Component\Form\Transformer\DeliveryMethodTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class ShippingType extends AbstractType
{
    /**
     * @var DeliveryPool
     */
    protected $deliveryPool;

    /**
     * @var ServiceDeliverySelectorInterface
     */
    protected $deliverySelector;

    public function __construct(DeliveryPool $deliveryPool, ServiceDeliverySelectorInterface $deliverySelector)
    {
        $this->deliverySelector = $deliverySelector;
        $this->deliveryPool = $deliveryPool;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $basket = $builder->getData();

        if (!$basket instanceof BasketInterface) {
            throw new \RuntimeException('Please provide a BasketInterface instance');
        }

        $methods = $this->deliverySelector->getAvailableMethods($basket, $basket->getDeliveryAddress());

        if (0 === \count($methods)) {
            throw new UndeliverableCountryException($basket->getDeliveryAddress());
        }

        $choices = [];
        foreach ($methods as $method) {
            $choices[$method->getName()] = $method->getCode();
        }

        reset($methods);

        $method = $basket->getDeliveryMethod() ?: current($methods);
        $basket->setDeliveryMethod($method ?: null);

        $sub = $builder->create('deliveryMethod', ChoiceType::class, [
            'expanded' => true,
            'choices' => $choices,
        ]);

        $sub->addViewTransformer(new DeliveryMethodTransformer($this->deliveryPool), true);

        $builder->add($sub);
    }

    public function getBlockPrefix()
    {
        return 'easy_shop_basket_shipping';
    }
}
