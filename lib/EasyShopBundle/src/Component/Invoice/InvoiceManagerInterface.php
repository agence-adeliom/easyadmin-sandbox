<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Invoice;

use Adeliom\EasyShop\DatagridBundle\Pager\PageableInterface;
use Adeliom\EasyShop\Doctrine\Model\ManagerInterface;

interface InvoiceManagerInterface extends ManagerInterface, PageableInterface
{
}
