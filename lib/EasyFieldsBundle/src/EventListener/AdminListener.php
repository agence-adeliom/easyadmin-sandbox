<?php
namespace Adeliom\EasyFieldsBundle\EventListener;

use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class AdminListener
{
    public function onKernelResponse(ResponseEvent $event)
    {
        $request = $event->getRequest();
        $response = $event->getResponse();

        if($request->query->has("crudAction")){
            $response->headers->set("X-CRUD-ACTION", $request->query->get("crudAction"));
        }

        if($request->query->has("crudControllerFqcn")){
            $response->headers->set("X-CRUD-CONTROLLER", $request->query->get("crudControllerFqcn"));
        }

        if($request->get('easyadmin_context')){
            /**
             * @var AdminContext $context
             */
            $context = $request->get('easyadmin_context');
            if($context->getEntity()){
                $response->headers->set("X-CRUD-ENTITY", $context->getEntity()->getFqcn());
                if ($context->getEntity()->getPrimaryKeyValueAsString()){
                    $response->headers->set("X-CRUD-ENTITY-ID", $context->getEntity()->getPrimaryKeyValueAsString());
                }
                if (method_exists($context->getEntity()->getInstance() , '__toString')){
                    $response->headers->set("X-CRUD-ENTITY-NAME", (string) $context->getEntity()->getInstance());
                }else{
                    $response->headers->set("X-CRUD-ENTITY-NAME", $context->getEntity()->toString());
                }
            }
        }
    }
}
