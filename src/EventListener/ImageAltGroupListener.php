<?php

declare(strict_types=1);

namespace App\EventListener;

use Adeliom\EasyMediaBundle\Event\EasyMediaGenerateAllAlt;
use Adeliom\EasyMediaBundle\Event\EasyMediaGenerateAltGroup;
use Adeliom\EasyMediaBundle\Service\EasyMediaManager;
use App\Message\EasyMediaGenerateAltMessage;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsEventListener(EasyMediaGenerateAltGroup::NAME)]
class ImageAltGroupListener
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    public function __invoke(EasyMediaGenerateAltGroup $event): void
    {
        $files = $event->getFiles();
        if ($files !== []) {
            foreach ($files as $file) {
                $this->messageBus->dispatch(new EasyMediaGenerateAltMessage($file));
            }
        }
    }
}
