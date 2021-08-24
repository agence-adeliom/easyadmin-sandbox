<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\OrderBundle\Entity;

use Adeliom\EasyShop\Component\Order\OrderElementManagerInterface;
use Adeliom\EasyShop\Doctrine\Entity\BaseEntityManager;

class OrderElementManager extends BaseEntityManager implements OrderElementManagerInterface
{
}
