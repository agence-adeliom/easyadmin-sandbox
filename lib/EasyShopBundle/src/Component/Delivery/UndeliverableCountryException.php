<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Delivery;

use Adeliom\EasyShop\Component\Customer\AddressInterface;


class UndeliverableCountryException extends \RuntimeException
{
    /**
     * @var AddressInterface
     */
    private $address;

    /**
     * @param int        $code
     * @param \Exception $previous
     */
    public function __construct(AddressInterface $address, $code = 0, ?\Exception $previous = null)
    {
        $this->address = $address;

        $message = sprintf("Some elements in your basket cannot be delivered in country '%s'.", $address->getCountryCode());
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return AddressInterface
     */
    public function getAddress()
    {
        return $this->address;
    }
}
