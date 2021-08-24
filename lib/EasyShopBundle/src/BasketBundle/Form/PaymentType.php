<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\BasketBundle\Form;

use Adeliom\EasyShop\Component\Basket\BasketInterface;
use Adeliom\EasyShop\Component\Customer\AddressInterface;
use Adeliom\EasyShop\Component\Customer\AddressManagerInterface;
use Adeliom\EasyShop\Component\Form\Transformer\PaymentMethodTransformer;
use Adeliom\EasyShop\Component\Payment\PaymentSelectorInterface;
use Adeliom\EasyShop\Component\Payment\Pool as PaymentPool;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class PaymentType extends AbstractType
{
    /**
     * @var AddressManagerInterface
     */
    protected $addressManager;

    /**
     * @var PaymentPool
     */
    protected $paymentPool;

    /**
     * @var PaymentSelectorInterface
     */
    protected $paymentSelector;

    public function __construct(AddressManagerInterface $addressManager, PaymentPool $paymentPool, PaymentSelectorInterface $paymentSelector)
    {
        $this->addressManager = $addressManager;
        $this->paymentSelector = $paymentSelector;
        $this->paymentPool = $paymentPool;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $basket = $builder->getData();

        if (!$basket instanceof BasketInterface) {
            throw new \RunTimeException('Please provide a BasketInterface instance');
        }

        $addresses = $this->addressManager->findBy([
            'customer' => $basket->getCustomer()->getId(),
            'type' => AddressInterface::TYPE_BILLING,
        ]);

        /*
         * TODO: implement billing address choice
        $builder->add('billingAddress', 'entity', array(
            'class' => $this->addressManager->getClass(),
            'choices' => $addresses,
            'expanded' => true,
        ));

         */
        $address = $basket->getBillingAddress() ?: current($addresses);
        $basket->setBillingAddress($address ?: null);

        $methods = $this->paymentSelector->getAvailableMethods($basket, $basket->getDeliveryAddress());

        $choices = [];
        foreach ($methods as $method) {
            $choices[$method->getName()] = $method->getCode();
        }

        reset($methods);

        $method = $basket->getPaymentMethod() ?: current($methods);
        $basket->setPaymentMethod($method ?: null);

        $sub = $builder->create('paymentMethod', ChoiceType::class, [
            'expanded' => true,
            'choices' => $choices,
        ]);

        $sub->addViewTransformer(new PaymentMethodTransformer($this->paymentPool), true);

        $builder->add($sub);
    }

    public function getBlockPrefix()
    {
        return 'easy_shop_basket_payment';
    }
}
