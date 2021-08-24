<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\PaymentBundle\Controller;

use Doctrine\ORM\EntityNotFoundException;
use Adeliom\EasyShop\Component\Basket\Basket;
use Adeliom\EasyShop\Component\Basket\BasketFactoryInterface;
use Adeliom\EasyShop\Component\Payment\InvalidTransactionException;
use Adeliom\EasyShop\Component\Payment\PaymentHandlerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class PaymentController extends AbstractController
{
    /**
     * @var BasketFactoryInterface
     */
    private $basketFactory;

    /**
     * @var PaymentHandlerInterface
     */
    private $paymentHandler;

    /**
     * @var Basket
     */
    private $basket;

    public function __construct(?BasketFactoryInterface $basketFactory = null, ?PaymentHandlerInterface $paymentHandler = null, ?Basket $basket = null)
    {
        if (!$basketFactory) {
            @trigger_error(sprintf(
                'Not providing a %s instance to %s is deprecated since sonata-project/ecommerce 3.1.0. Providing it will be mandatory in 4.0',
                BasketFactoryInterface::class,
                __METHOD__
            ), \E_USER_DEPRECATED);
        }

        if (!$paymentHandler) {
            @trigger_error(sprintf(
                'Not providing a %s instance to %s is deprecated since sonata-project/ecommerce 3.1.0. Providing it will be mandatory in 4.0',
                PaymentHandlerInterface::class,
                __METHOD__
            ), \E_USER_DEPRECATED);
        }

        if (!$basket) {
            @trigger_error(sprintf(
                'Not providing a %s instance to %s is deprecated since sonata-project/ecommerce 3.1.0. Providing it will be mandatory in 4.0',
                Basket::class,
                __METHOD__
            ), \E_USER_DEPRECATED);
        }

        $this->basketFactory = $basketFactory;
        $this->paymentHandler = $paymentHandler;
        $this->basket = $basket;
    }

    /**
     * This action is called by the user after the sendbank
     * In most case the order is already cancelled by a previous callback.
     *
     * @throws UnauthorizedHttpException
     * @throws NotFoundHttpException
     *
     * @return Response
     */
    public function errorAction(Request $request)
    {
        try {
            $order = $this->getPaymentHandler()->handleError($request, $this->getBasket());
        } catch (EntityNotFoundException $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        } catch (InvalidTransactionException $ex) {
            throw new UnauthorizedHttpException($ex->getMessage());
        }

        return $this->render('@SonataPayment/Payment/error.html.twig', [
            'order' => $order,
        ]);
    }

    /**
     * @throws NotFoundHttpException
     * @throws UnauthorizedHttpException
     *
     * @return Response
     */
    public function confirmationAction(Request $request)
    {
        try {
            $order = $this->getPaymentHandler()->handleConfirmation($request);
        } catch (EntityNotFoundException $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        } catch (InvalidTransactionException $ex) {
            throw new UnauthorizedHttpException($ex->getMessage());
        }

        if (!($order->isValidated() || $order->isPending())) {
            return $this->render('@SonataPayment/Payment/error.html.twig', [
                'order' => $order,
            ]);
        }

        return $this->render('@SonataPayment/Payment/confirmation.html.twig', [
            'order' => $order,
        ]);
    }

    /**
     * this action redirect the user to the bank.
     *
     * @return Response
     */
    public function sendbankAction(Request $request)
    {
        $basket = $this->getBasket();

        if ('POST' !== $request->getMethod()) {
            return $this->redirect($this->generateUrl('easy_shop_basket_index'));
        }

        if (!$basket->isValid()) {
            $this->get('session')->getFlashBag()->set(
                'error',
                $this->container->get('translator')->trans('basket_not_valid', [], 'SonataPaymentBundle')
            );

            return $this->redirect($this->generateUrl('easy_shop_basket_index'));
        }

        $payment = $basket->getPaymentMethod();

        // check if the basket is valid/compatible with the bank gateway
        if (!$payment->isBasketValid($basket)) {
            $this->get('session')->getFlashBag()->set(
                'error',
                $this->container->get('translator')->trans('basket_not_valid_with_current_payment_method', [], 'SonataPaymentBundle')
            );

            return $this->redirect($this->generateUrl('easy_shop_basket_index'));
        }

        // transform the basket into order
        $order = $this->getPaymentHandler()->getSendbankOrder($basket);
        $this->getBasketFactory()->reset($basket);

        // the payment must handle everything when calling the bank
        return $payment->sendbank($order);
    }

    /**
     * this action handler the callback sent from the bank.
     *
     * @throws NotFoundHttpException
     * @throws UnauthorizedHttpException
     *
     * @return Response
     */
    public function callbackAction(Request $request)
    {
        try {
            $response = $this->getPaymentHandler()->getPaymentCallbackResponse($request);
        } catch (EntityNotFoundException $ex) {
            throw new NotFoundHttpException($ex->getMessage());
        } catch (InvalidTransactionException $ex) {
            throw new UnauthorizedHttpException($ex->getMessage());
        }

        return $response;
    }

    /**
     * @return Response
     */
    public function termsAction()
    {
        return $this->render('@SonataPayment/Payment/terms.html.twig');
    }

    /**
     * @return BasketFactoryInterface
     */
    protected function getBasketFactory()
    {
        if ($this->basketFactory instanceof BasketFactoryInterface) {
            return $this->basketFactory;
        }

        return $this->get('easy_shop.basket.factory');
    }

    /**
     * @return Basket
     */
    protected function getBasket()
    {
        if ($this->basket instanceof Basket) {
            return $this->basket;
        }

        return $this->get('easy_shop.basket');
    }

    /**
     * @return PaymentHandlerInterface
     */
    protected function getPaymentHandler()
    {
        if ($this->paymentHandler instanceof PaymentHandlerInterface) {
            return $this->paymentHandler;
        }

        return $this->get('easy_shop.payment.handler');
    }
}
