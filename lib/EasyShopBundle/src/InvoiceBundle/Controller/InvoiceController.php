<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\InvoiceBundle\Controller;

use Adeliom\EasyShop\Component\Customer\CustomerInterface;
use Adeliom\EasyShop\Component\Invoice\InvoiceManagerInterface;
use Adeliom\EasyShop\Component\Order\OrderManagerInterface;
use Adeliom\EasyShop\Component\Transformer\InvoiceTransformer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class InvoiceController extends AbstractController
{
    /**
     * @throws \RuntimeException
     */
    public function indexAction(): void
    {
        throw new \RuntimeException('not implemented');
    }

    /**
     * @param string $reference
     *
     * @throws AccessDeniedException
     *
     * @return Response
     */
    public function viewAction($reference)
    {
        $order = $this->getOrderManager()->findOneBy(['reference' => $reference]);

        if (null === $order) {
            throw new AccessDeniedException();
        }

        $this->checkAccess($order->getCustomer());

        $invoice = $this->getInvoiceManager()->findOneBy(['reference' => $reference]);

        if (null === $invoice) {
            $invoice = $this->getInvoiceManager()->create();

            $this->getInvoiceTransformer()->transformFromOrder($order, $invoice);
            $this->getInvoiceManager()->save($invoice);
        }

        $this->get('sonata.seo.page')->setTitle($this->get('translator')->trans('invoice_view_title', [], 'SonataInvoiceBundle'));

        return $this->render('@SonataInvoice/Invoice/view.html.twig', [
            'invoice' => $invoice,
            'order' => $order,
        ]);
    }

    /**
     * @param string $reference
     *
     * @throws \RuntimeException
     */
    public function downloadAction($reference): void
    {
        throw new \RuntimeException('not implemented');
    }

    /**
     * Checks that the current logged in user has access to given invoice.
     *
     * @param CustomerInterface $customer The linked customer
     *
     * @throws AccessDeniedException
     */
    protected function checkAccess(CustomerInterface $customer): void
    {
        if (!($user = $this->getUser())
            || !$customer->getUser()
            || $customer->getUser()->getId() !== $user->getId()) {
            throw new AccessDeniedException();
        }
    }

    /**
     * @return InvoiceManagerInterface
     */
    protected function getInvoiceManager()
    {
        return $this->get('easy_shop.invoice.manager');
    }

    /**
     * @return OrderManagerInterface
     */
    protected function getOrderManager()
    {
        return $this->get('easy_shop.order.manager');
    }

    /**
     * @return InvoiceTransformer
     */
    protected function getInvoiceTransformer()
    {
        return $this->get('easy_shop.payment.transformer.invoice');
    }
}
