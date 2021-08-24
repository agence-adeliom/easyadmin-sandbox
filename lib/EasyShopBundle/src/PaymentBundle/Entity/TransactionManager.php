<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\PaymentBundle\Entity;

use Adeliom\EasyShop\Component\Payment\TransactionManagerInterface;
use Adeliom\EasyShop\Doctrine\Entity\BaseEntityManager;

class TransactionManager extends BaseEntityManager implements TransactionManagerInterface
{
}
