<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Payment\Scellius;

use Adeliom\EasyShop\Component\Order\OrderInterface;

interface ScelliusTransactionGeneratorInterface
{
    /**
     * @return string
     */
    public function generate(OrderInterface $order);
}
