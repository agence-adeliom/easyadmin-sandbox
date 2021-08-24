<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\PaymentBundle\Controller;

use Adeliom\EasyShop\Component\Order\OrderInterface;
use Adeliom\EasyShop\Component\Payment\Debug\DebugPayment;
use Adeliom\EasyShop\Component\Payment\InvalidTransactionException;
use Adeliom\EasyShop\OrderBundle\Entity\OrderManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\RouterInterface;


class DebugPaymentController extends AbstractController
{
    /**
     * User choice action.
     *
     * @return Response
     */
    public function paymentAction(Request $request)
    {
        $order = $this->checkRequest();

        return $this->render('@SonataPayment/Payment/debug.html.twig', [
            'order' => $order,
            'check' => $request->get('check'),
        ]);
    }

    /**
     * Process the User choice.
     *
     * @return Response
     */
    public function processPaymentAction(Request $request)
    {
        $order = $this->checkRequest();

        return $this->getDebugPayment()->processCallback($order, $request->get('action'));
    }

    /**
     * Check the Request and return the current Order.
     *
     * @throws InvalidTransactionException
     * @throws NotFoundHttpException
     * @throws \RuntimeException
     *
     * @return OrderInterface
     */
    protected function checkRequest(Request $request)
    {
        if ('prod' === $this->getKernel()->getEnvironment()) {
            throw new \RuntimeException('Debug Payment is not authorized in production environment.');
        }

        $reference = $request->get('reference');

        $order = $this->getOrderManager()->findOneBy(['reference' => $reference]);

        if (!$order) {
            throw new NotFoundHttpException(sprintf('Order with reference "%s" not found.', $reference));
        }

        if ($request->get('check') !== $this->getDebugPayment()->generateUrlCheck($order)) {
            throw new InvalidTransactionException($reference);
        }

        return $order;
    }

    /**
     * @return DebugPayment
     */
    protected function getDebugPayment()
    {
        return $this->get('easy_shop.payment.method.debug');
    }

    /**
     * @return OrderManager
     */
    protected function getOrderManager()
    {
        return $this->get('easy_shop.order.manager');
    }

    /**
     * @return KernelInterface
     */
    protected function getKernel()
    {
        return $this->get('kernel');
    }

    /**
     * @return RouterInterface
     */
    protected function getRouter()
    {
        return $this->get('router');
    }
}
