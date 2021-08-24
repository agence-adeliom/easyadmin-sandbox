<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Event;

use Adeliom\EasyShop\Component\Invoice\InvoiceInterface;
use Symfony\Contracts\EventDispatcher\Event;


class InvoiceTransformEvent extends Event
{
    /**
     * @var InvoiceInterface
     */
    protected $invoice;

    public function __construct(InvoiceInterface $invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * @return InvoiceInterface
     */
    public function getInvoice()
    {
        return $this->invoice;
    }
}
