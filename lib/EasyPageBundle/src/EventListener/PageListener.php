<?php

namespace Adeliom\EasyPageBundle\EventListener;

use Adeliom\EasyPageBundle\Event\EasyPageEvent;


class PageListener
{
    public function __invoke(EasyPageEvent $event): void
    {
        $event->setTemplate("toto");
    }
}
