<?php

namespace App\EventListener;

use Adeliom\EasyMediaBundle\Event\EasyMediaBeforeSetMetas;
use App\Services\AltGenerator\AltGeneratorInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(EasyMediaBeforeSetMetas::NAME)]
class ImageMetasListener
{
    public function __construct(
        #[Autowire(service: 'gptAltGenerator')]
        private AltGeneratorInterface $altGenerator,
    ) {
    }

    public function __invoke(EasyMediaBeforeSetMetas $event): void
    {
        $entity = $event->getEntity();
        $source = $event->getSource();
        if (@exif_imagetype($source->getPathname())) {
            $metas = array_merge($event->getMetas(), [
                'alt' => $this->altGenerator->generate($entity, $source),
            ]);
            $event->setMetas($metas);
        }
    }
}
