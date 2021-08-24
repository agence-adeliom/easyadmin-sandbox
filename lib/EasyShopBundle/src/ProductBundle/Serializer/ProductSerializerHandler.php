<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\ProductBundle\Serializer;

use Adeliom\EasyShop\Form\Serializer\BaseSerializerHandler;


class ProductSerializerHandler
{
    public static function getType()
    {
        return 'easy_shop_product_product_id';
    }
}
