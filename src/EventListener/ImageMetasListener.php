<?php

declare(strict_types=1);

namespace App\EventListener;

use Adeliom\EasyMediaBundle\Event\EasyMediaBeforeSetMetas;
use App\Services\AltGenerator\AltGeneratorInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\File\File;

#[AsEventListener(EasyMediaBeforeSetMetas::NAME)]
class ImageMetasListener
{
    public function __construct(
        private readonly AltGeneratorInterface $altGenerator,
    ) {
    }

    public function __invoke(EasyMediaBeforeSetMetas $event): void
    {
        if (isset($event->getMetas()['alt']) && ! empty($event->getMetas()['alt'])) {
            return;
        }

        $entity = $event->getEntity();
        $source = $event->getSource();
        if (
            ($source instanceof File && exif_imagetype($source->getPathname())) ||
            (is_string($source) && filter_var($source, FILTER_VALIDATE_URL))
        ) {
            $metas = array_merge($event->getMetas(), [
                'alt' => $this->altGenerator->generate($entity, $source),
            ]);
            $event->setMetas($metas);
        }
    }
}
