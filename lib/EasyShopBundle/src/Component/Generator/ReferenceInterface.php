<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Generator;

use Adeliom\EasyShop\Component\Invoice\InvoiceInterface;
use Adeliom\EasyShop\Component\Order\OrderInterface;

interface ReferenceInterface
{
    /**
     * Append a valid reference number to the invoice, the order must be persisted first.
     *
     * @throws \RuntimeException
     */
    public function invoice(InvoiceInterface $invoice);

    /**
     * Append a valid reference number to the order, the order must be persisted first.
     *
     * @throws \RuntimeException
     */
    public function order(OrderInterface $order);
}
