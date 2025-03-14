<?php

namespace App\EventListener;

use Adeliom\EasyMediaBundle\Event\EasyMediaGenerateAllAlt;
use Adeliom\EasyMediaBundle\Service\EasyMediaManager;
use App\Message\EasyMediaGenerateAltMessage;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsEventListener(EasyMediaGenerateAllAlt::NAME)]
class ImageAllAltListener
{
    public function __construct(
        private readonly EasyMediaManager $easyMediaManager,
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    public function __invoke(EasyMediaGenerateAllAlt $event): void
    {
        $medias = $this->easyMediaManager->getHelper()->getMediaRepository()->findAll();
        if (!empty($medias)) {
            foreach ($medias as $media) {
                $this->messageBus->dispatch(new EasyMediaGenerateAltMessage($media->getId()));
            }
        }
    }
}
