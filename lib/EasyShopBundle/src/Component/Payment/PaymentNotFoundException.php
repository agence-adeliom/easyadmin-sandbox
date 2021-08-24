<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Payment;


class PaymentNotFoundException extends \InvalidArgumentException
{
    /**
     * @param string     $bankCode
     * @param int        $code
     * @param \Exception $previous
     */
    public function __construct($bankCode, $code = 0, ?\Exception $previous = null)
    {
        $message = sprintf("Payment method with code '%s' was not found", $bankCode);
        parent::__construct($message, $code, $previous);
    }
}
