<?php

namespace Adeliom\EasyMenuBundle\Entity;

use Adeliom\EasyCommonBundle\Traits\EntityIdTrait;
use Adeliom\EasyCommonBundle\Traits\EntityStatusTrait;
use Adeliom\EasyCommonBundle\Traits\EntityTimestampableTrait;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\HasLifecycleCallbacks()
 * @ORM\MappedSuperclass(repositoryClass="Adeliom\EasyMenuBundle\Repository\BaseMenuRepository")
 */
class BaseMenuEntity {

    use EntityIdTrait;
    use EntityTimestampableTrait {
        EntityTimestampableTrait::__construct as private __TimestampableConstruct;
    }

    use EntityStatusTrait;

    /**
     * @var BaseMenuItemEntity[] | null
     */
    protected $items;

    public function __construct()
    {
        $this->__TimestampableConstruct();
        $this->items     = new ArrayCollection();
    }

    /**
     * @return BaseMenuItemEntity[]|ArrayCollection
     */
    public function getItems()
    {
        return $this->items;
    }

    public function addItem(BaseMenuItemEntity $item): void
    {
        $this->items->add($item);
        if ($item->getMenu() !== $this) {
            $item->setMenu($this);
        }
    }

    public function removeItem(BaseMenuItemEntity $item): void
    {
        $this->items->removeElement($item);
        $item->setMenu(null);
    }

    /**
     * @ORM\PreRemove()
     */
    public function onRemove(LifecycleEventArgs $event): void
    {
        $this->setStatus(false);
    }
}
