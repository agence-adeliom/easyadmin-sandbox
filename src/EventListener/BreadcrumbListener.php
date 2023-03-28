<?php

namespace App\EventListener;

use Symfony\Contracts\EventDispatcher\Event;


class BreadcrumbListener {
    public function __invoke(Event $event): void
    {
        dump($event);
    }
}
