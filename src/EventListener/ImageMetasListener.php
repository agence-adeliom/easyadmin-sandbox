<?php

namespace App\EventListener;

use Adeliom\EasyMediaBundle\Event\EasyMediaBeforeSetMetas;
use App\Services\AltGeneratorService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(EasyMediaBeforeSetMetas::NAME)]
class ImageMetasListener
{

    public function __construct(
        private AltGeneratorService $altGenerator,
    ) {
    }

    public function __invoke(EasyMediaBeforeSetMetas $event): void
    {
        $entity = $event->getEntity();
        $source = $event->getSource();
        if (@exif_imagetype($source->getPathname())) {
            $metas = array_merge($event->getMetas(), [
                'alt' => $this->altGenerator->generate($entity),
            ]);
            $event->setMetas($metas);
        }
    }
}
