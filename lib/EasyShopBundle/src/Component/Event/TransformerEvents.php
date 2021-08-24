<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Event;


final class TransformerEvents
{
    public const PRE_BASKET_TO_ORDER_TRANSFORM = 'sonata.ecommerce.pre_basket_to_order_transform';
    public const POST_BASKET_TO_ORDER_TRANSFORM = 'sonata.ecommerce.post_basket_to_order_transform';
    public const PRE_ORDER_TO_BASKET_TRANSFORM = 'sonata.ecommerce.pre_order_to_basket_transform';
    public const POST_ORDER_TO_BASKET_TRANSFORM = 'sonata.ecommerce.post_order_to_basket_transform';
    public const PRE_ORDER_TO_INVOICE_TRANSFORM = 'sonata.ecommerce.pre_order_to_invoice_transform';
    public const POST_ORDER_TO_INVOICE_TRANSFORM = 'sonata.ecommerce.post_order_to_invoice_transform';
}
