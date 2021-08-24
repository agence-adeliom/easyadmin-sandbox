<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\InvoiceBundle\Entity;

use Adeliom\EasyShop\Component\Invoice\InvoiceElementManagerInterface;
use Adeliom\EasyShop\Doctrine\Entity\BaseEntityManager;

class InvoiceElementManager extends BaseEntityManager implements InvoiceElementManagerInterface
{
}
