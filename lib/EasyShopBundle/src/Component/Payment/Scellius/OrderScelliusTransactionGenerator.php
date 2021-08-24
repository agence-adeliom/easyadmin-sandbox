<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Payment\Scellius;

use Adeliom\EasyShop\Component\Order\OrderInterface;

/**
 * This method returns the sequence number from the Order reference (only works if the generator is the MysqlReference)
 *   => ie: YYMMDDXXXXXX.
 */
class OrderScelliusTransactionGenerator implements ScelliusTransactionGeneratorInterface
{
    /**
     * @throws \RuntimeException
     *
     * @return string
     */
    public function generate(OrderInterface $order)
    {
        if (12 !== \strlen($order->getReference())) {
            throw new \RuntimeException('Invalid reference length');
        }

        return substr($order->getReference(), -6);
    }
}
