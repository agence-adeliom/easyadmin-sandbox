<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\ProductBundle\Entity;

use Adeliom\EasyShop\Component\Product\DeliveryManagerInterface;
use Adeliom\EasyShop\Doctrine\Entity\BaseEntityManager;

class DeliveryManager extends BaseEntityManager implements DeliveryManagerInterface
{
}
