<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\OrderBundle\Serializer;

use Adeliom\EasyShop\Form\Serializer\BaseSerializerHandler;


class OrderSerializerHandler
{
    public static function getType()
    {
        return 'sonata_order_order_id';
    }
}
