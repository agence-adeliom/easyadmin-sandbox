<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Payment;

use Adeliom\EasyShop\Component\Basket\BasketInterface;
use Symfony\Component\HttpFoundation\Request;

interface PaymentHandlerInterface
{
    /**
     * Processes the request to generate the transaction related to the error and returns associated order.
     *
     * @throws \Doctrine\ORM\EntityNotFoundException
     * @throws InvalidTransactionException
     *
     * @return \Adeliom\EasyShop\Component\Order\OrderInterface
     */
    public function handleError(Request $request, BasketInterface $basket);

    /**
     * Returns the order for given confirmation request and checks the validity.
     *
     * @throws \Doctrine\ORM\EntityNotFoundException
     * @throws InvalidTransactionException
     *
     * @return \Adeliom\EasyShop\Component\Order\OrderInterface
     */
    public function handleConfirmation(Request $request);

    /**
     * Creates the order based on current basket & resets the basket.
     *
     * @return \Adeliom\EasyShop\Component\Order\OrderInterface
     */
    public function getSendbankOrder(BasketInterface $basket);

    /**
     * Returns the callback response of current payment mean once everything validated.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getPaymentCallbackResponse(Request $request);
}
