<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Basket;

use Adeliom\EasyShop\Component\Customer\CustomerInterface;
use Adeliom\EasyShop\DatagridBundle\Pager\PageableInterface;
use Adeliom\EasyShop\Doctrine\Model\ManagerInterface;

interface BasketManagerInterface extends ManagerInterface, PageableInterface
{
    /**
     * @return BasketInterface|null
     */
    public function loadBasketPerCustomer(CustomerInterface $customer);
}
