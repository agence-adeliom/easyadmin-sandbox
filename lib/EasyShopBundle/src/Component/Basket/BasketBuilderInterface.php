<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Basket;

interface BasketBuilderInterface
{
    /**
     * Build a basket.
     *
     * @param \Adeliom\EasyShop\Component\Basket\BasketInterface $basket
     */
    public function build(BasketInterface $basket);
}
