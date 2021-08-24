<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Event;


final class BasketEvents
{
    public const PRE_ADD_PRODUCT = 'sonata.ecommerce.basket.pre_add_product';
    public const POST_ADD_PRODUCT = 'sonata.ecommerce.basket.post_add_product';

    public const PRE_MERGE_PRODUCT = 'sonata.ecommerce.basket.pre_merge_product';
    public const POST_MERGE_PRODUCT = 'sonata.ecommerce.basket.post_merge_product';

    public const PRE_CALCULATE_PRICE = 'sonata.ecommerce.basket.pre_calculate_price';
    public const POST_CALCULATE_PRICE = 'sonata.ecommerce.basket.post_calculate_price';
}
