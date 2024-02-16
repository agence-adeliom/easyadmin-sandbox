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
        #[Autowire(service: 'gptAltGenerator')]
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
            $url = $_SERVER['HTTP_ORIGIN'] . $this->easyMediaManager->publicUrl($media);
            if ($this->easyMediaManager->getHelper()->fileIsType($media, 'image')) {
                $newAlt = $this->altGenerator->generate($media, $url);
                $metas = $media->getMetas();
                if (empty($metas['alt']) || $newAlt !== $metas['alt']) {
                    $metas['alt'] = $newAlt;
                    $media->setMetas($metas);
                    $this->entityManager->persist($media);
                    $this->entityManager->flush();
                }
            }
        }
    }

}
