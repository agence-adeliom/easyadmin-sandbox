<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Payment\Scellius;

use Adeliom\EasyShop\Component\Order\OrderInterface;

/**
 * This method returns none, so the request binary will generates one for use.
 */
class NodeScelliusTransactionGenerator implements ScelliusTransactionGeneratorInterface
{
    /**
     * @return string
     */
    public function generate(OrderInterface $order)
    {
        return '';
    }
}
