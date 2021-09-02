<?php

namespace Adeliom\EasyFaqBundle\EventListener;


use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * This class adds automatically the ManyToOne and OneToMany relations in Page and Category entities,
 * because it's normally impossible to do so in a mapped superclass.
 */
class DoctrineMappingListener implements EventSubscriber
{
    /**
     * @var string
     */
    private $entryClass;

    /**
     * @var string
     */
    private $categoryClass;

    public function __construct(string $entryClass, string $categoryClass)
    {
        $this->entryClass = $entryClass;
        $this->categoryClass = $categoryClass;
    }

    public function getSubscribedEvents()
    {
        return [Events::loadClassMetadata];
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs): void
    {
        /** @var ClassMetadata $classMetadata */
        $classMetadata = $eventArgs->getClassMetadata();

        $isEntry     = is_a($classMetadata->getName(), $this->entryClass, true);
        $isCategory = is_a($classMetadata->getName(), $this->categoryClass, true);

        if ($isEntry) {
            $this->processEntryMetadata($classMetadata);
        }

        if ($isCategory) {
            $this->processCategoryMetadata($classMetadata);
        }
    }

    private function processEntryMetadata(ClassMetadata $classMetadata): void
    {
        if (!$classMetadata->hasAssociation('category')) {
            $classMetadata->mapManyToOne([
                'fieldName' => 'category',
                'targetEntity' => $this->categoryClass,
                'inversedBy' => 'entries',
            ]);
        }
    }

    private function processCategoryMetadata(ClassMetadata $classMetadata): void
    {
        if (!$classMetadata->hasAssociation('pages')) {
            $classMetadata->mapOneToMany([
                'fieldName' => 'entries',
                'targetEntity' => $this->entryClass,
                'mappedBy' => 'category',
            ]);
        }

    }
}
