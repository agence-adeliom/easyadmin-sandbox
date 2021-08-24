<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\BasketBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class GlobalVariables
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getBasket()
    {
        return $this->container->get('easy_shop.basket');
    }

    public function getCustomer()
    {
        return $this->getBasket()->getCustomer();
    }
}
