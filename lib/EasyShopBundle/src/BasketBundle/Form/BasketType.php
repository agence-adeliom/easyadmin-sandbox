<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\BasketBundle\Form;

use Adeliom\EasyShop\Component\Basket\BasketInterface;
use Adeliom\EasyShop\Component\Form\EventListener\BasketResizeFormListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;

class BasketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // always clone the basket, so the one in session is never altered
        $basket = $builder->getData();

        if (!$basket instanceof BasketInterface) {
            throw new \RunTimeException('Please provide a BasketInterface instance');
        }

        // should create a custom basket elements here
        $basketElementBuilder = $builder->create('basketElements', FormType::class, [
            'by_reference' => false,
        ]);
        $basketElementBuilder->addEventSubscriber(new BasketResizeFormListener($builder->getFormFactory(), $basket));
        $builder->add($basketElementBuilder);
    }

    public function getBlockPrefix()
    {
        return 'easy_shop_basket_basket';
    }
}
