<?php

namespace App\EventListener;

use Adeliom\EasyMediaBundle\Event\EasyMediaGenerateAlt;
use Adeliom\EasyMediaBundle\Service\EasyMediaManager;
use App\Services\AltGenerator\AltGeneratorInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(EasyMediaGenerateAlt::NAME)]
class ImageAltListener
{

    public function __construct(
        #[Autowire(service: 'gptAltGenerator')]
        private AltGeneratorInterface $altGenerator,
        private EasyMediaManager $easyMediaManager,
    ) {
    }

    public function __invoke(EasyMediaGenerateAlt $event): void
    {
        $entity = $event->getEntity();
        $url = $_SERVER['HTTP_ORIGIN'] . $this->easyMediaManager->publicUrl($entity);
        if ($this->easyMediaManager->getHelper()->fileIsType($entity, 'image')) {
            $alt = $this->altGenerator->generate($entity, $url);
            $event->setAlt($alt);
        }
    }
}
