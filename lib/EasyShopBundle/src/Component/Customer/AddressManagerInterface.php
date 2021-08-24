<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Customer;

use Adeliom\EasyShop\DatagridBundle\Pager\PageableInterface;
use Adeliom\EasyShop\Doctrine\Model\ManagerInterface;

interface AddressManagerInterface extends ManagerInterface, PageableInterface
{
    /**
     * Sets $address the current customer address.
     */
    public function setCurrent(AddressInterface $address);
}
