<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Payment;


class InvalidTransactionException extends \InvalidArgumentException
{
    /**
     * @param string     $orderReference
     * @param int        $code
     * @param \Exception $previous
     */
    public function __construct($orderReference = null, $code = 0, ?\Exception $previous = null)
    {
        $message = $orderReference ? sprintf('Invalid check - order ref: %s', $orderReference) : 'Unable to find reference';
        parent::__construct($message, $code, $previous);
    }
}
