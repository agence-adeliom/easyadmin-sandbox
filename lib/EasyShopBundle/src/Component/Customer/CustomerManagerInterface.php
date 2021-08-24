<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Customer;

use Adeliom\EasyShop\DatagridBundle\Pager\PageableInterface;
use Adeliom\EasyShop\Doctrine\Model\ManagerInterface;

interface CustomerManagerInterface extends ManagerInterface, PageableInterface
{
}
