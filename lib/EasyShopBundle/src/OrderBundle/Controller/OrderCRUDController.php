<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\OrderBundle\Controller;

use Adeliom\EasyShop\AdminBundle\Controller\CRUDController;
use Adeliom\EasyShop\Component\Invoice\InvoiceManagerInterface;
use Adeliom\EasyShop\Component\Transformer\InvoiceTransformer;
use Symfony\Component\Routing\Exception\InvalidParameterException;


class OrderCRUDController extends CRUDController
{
    public function generateInvoiceAction()
    {
        if (null === ($id = $this->getRequest()->get('id'))) {
            throw new InvalidParameterException("Missing 'id' parameter");
        }

        if (null === $this->getRequest()->get('confirm')) {
            return $this->render('@SonataOrder/OrderAdmin/invoice_generate_confirm.html.twig', ['id' => $id]);
        }

        $order = $this->admin->getObject($id);

        $invoice = $this->getInvoiceManager()->findOneBy(['reference' => $order->getReference()]);

        if (null === $invoice) {
            $invoice = $this->getInvoiceManager()->create();

            $this->getInvoiceTransformer()->transformFromOrder($order, $invoice);
            $this->getInvoiceManager()->save($invoice);

            $message = $this->get('translator')->trans('oRDER_TO_INVOICE_generate_success', [], 'SonataOrderBundle');
            $this->addFlash('easy_shop_flash_success', $message);
        }

        return $this->redirect($this->generateUrl('admin_easy_shop_invoice_invoice_edit', ['id' => $invoice->getId()]));
    }

    /**
     * @return InvoiceManagerInterface
     */
    protected function getInvoiceManager()
    {
        return $this->get('easy_shop.invoice.manager');
    }

    /**
     * @return InvoiceTransformer
     */
    protected function getInvoiceTransformer()
    {
        return $this->get('easy_shop.payment.transformer.invoice');
    }
}
