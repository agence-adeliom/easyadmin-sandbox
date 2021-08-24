<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\CustomerBundle\Serializer;

use Adeliom\EasyShop\Form\Serializer\BaseSerializerHandler;


class CustomerSerializerHandler
{
    public static function getType()
    {
        return 'easy_shop_customer_customer_id';
    }
}
