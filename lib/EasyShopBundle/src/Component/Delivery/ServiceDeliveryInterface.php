<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Delivery;

use Adeliom\EasyShop\Component\Basket\BasketInterface;

interface ServiceDeliveryInterface
{
    public const STATUS_OPEN = 1;    // Not processed yet
    public const STATUS_PENDING = 2;    // Packing
    public const STATUS_SENT = 3;    // In transit
    public const STATUS_CANCELLED = 4;    // Delivery cancelled
    public const STATUS_COMPLETED = 5;    // Delivered
    public const STATUS_RETURNED = 6;    // Returned to sender

    /**
     * @return float the delivery base price
     */
    public function getPrice();

    /**
     * Sets the VAT rate.
     *
     * @param float $vat
     */
    public function setVatRate($vat);

    /**
     * @return float the vat linked to the delivery
     */
    public function getVatRate();

    /**
     * @return string the name of the delivery method
     */
    public function getName();

    /**
     * @return bool return true an address is required to use this delivery method
     */
    public function isAddressRequired();

    /**
     * Return the delivery price.
     *
     * @param bool $vat
     *
     * @return float
     */
    public function getTotal(BasketInterface $basket, $vat = false);

    /**
     * Return the vat amount.
     *
     * @return float
     */
    public function getVatAmount(BasketInterface $basket);

    /**
     * @return string
     */
    public function getCode();

    /**
     * @return bool
     */
    public function getEnabled();

    /**
     * @return int
     */
    public function getPriority();
}
