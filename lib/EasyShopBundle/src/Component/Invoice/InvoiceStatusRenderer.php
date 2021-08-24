<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Invoice;

use Adeliom\EasyShop\Twig\Status\StatusClassRendererInterface;


class InvoiceStatusRenderer implements StatusClassRendererInterface
{
    public function handlesObject($object, $statusName = null)
    {
        return $object instanceof InvoiceInterface;
    }

    public function getStatusClass($object, $statusName = null, $default = '')
    {
        switch ($object->getStatus()) {
            case InvoiceInterface::STATUS_CONFLICT:
                return 'danger';
            case InvoiceInterface::STATUS_OPEN:
                return 'warning';
            case InvoiceInterface::STATUS_PAID:
                return 'success';
        }

        return $default;
    }
}
