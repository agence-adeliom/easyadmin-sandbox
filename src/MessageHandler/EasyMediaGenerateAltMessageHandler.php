<?php

declare(strict_types=1);

namespace App\MessageHandler;

use Adeliom\EasyMediaBundle\Service\EasyMediaManager;
use App\Message\EasyMediaGenerateAltMessage;
use App\Services\AltGenerator\AltGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class EasyMediaGenerateAltMessageHandler
{
    public function __construct(
        private AltGeneratorInterface $altGenerator,
        private EasyMediaManager $easyMediaManager,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(EasyMediaGenerateAltMessage $message): void
    {
        $mediaId = $message->getMediaId();
        $media = $this->easyMediaManager->getMedia($mediaId);
        if ($media) {
            $metas = $media->getMetas();
            if (!empty($metas['alt'])) {
                return;
            }

            if ($this->easyMediaManager->getHelper()->fileIsType($media, 'image')) {
                $url = $_SERVER['HTTP_ORIGIN'] . $this->easyMediaManager->publicUrl($media);
                $newAlt = $this->altGenerator->generate($media, $url);
                if ($newAlt !== $metas['alt']) {
                    $metas['alt'] = $newAlt;
                    $media->setMetas($metas);
                    $this->entityManager->persist($media);
                    $this->entityManager->flush();
                }
            }
        }
    }
}
