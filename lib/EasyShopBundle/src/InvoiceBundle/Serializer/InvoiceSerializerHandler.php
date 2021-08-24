<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\InvoiceBundle\Serializer;

use Adeliom\EasyShop\Form\Serializer\BaseSerializerHandler;


class InvoiceSerializerHandler
{
    public static function getType()
    {
        return 'easy_shop_invoice_invoice_id';
    }
}
