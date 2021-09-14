<?php

namespace Adeliom\EasyShopBundle\EventListener;

use App\Controller\Admin\DashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Option\EA;
use EasyCorp\Bundle\EasyAdminBundle\Exception\EntityRemoveException;
use EasyCorp\Bundle\EasyAdminBundle\Factory\AdminContextFactory;
use http\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class AdminExceptionListener
{
    /**
     * @var TranslatorInterface
     */
    private $translator;
    private $contextFactory;

    public function __construct(TranslatorInterface $translator, AdminContextFactory $contextFactory)
    {
        $this->translator = $translator;
        $this->contextFactory = $contextFactory;
    }

    public function onKernelRequest(RequestEvent $event){
        $request = $event->getRequest();
    }

    public function onKernelException(ExceptionEvent $event)
    {
        // You get the exception object from the received event
        $exception = $event->getThrowable();
        $request = $event->getRequest();

        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        if ($exception instanceof EntityRemoveException) {
            $entity = $exception->getContext()->getParameters()['entity_name'];
            $request->getSession()->getFlashBag()->add('danger', $this->translator->trans('sylius.resource.delete_error', [
                '%resource%' => $entity
            ], 'flashes'));
            $event->setResponse(new RedirectResponse($request->headers->get('referer')));
        }

    }
}
