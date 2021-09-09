<?php

namespace Adeliom\EasyMenuBundle\Entity;

use Adeliom\EasyCommonBundle\Enum\ThreeStateStatusEnum;
use Adeliom\EasyCommonBundle\Traits\EntityIdTrait;
use Adeliom\EasyCommonBundle\Traits\EntityPublishableTrait;
use Adeliom\EasyCommonBundle\Traits\EntityThreeStateStatusTrait;
use Adeliom\EasyCommonBundle\Traits\EntityTimestampableTrait;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\HasLifecycleCallbacks()
 * @ORM\MappedSuperclass(repositoryClass="Adeliom\EasyMenuBundle\Repository\BaseMenuItemRepository")
 */
class BaseMenuItemEntity {

    use EntityIdTrait;
    use EntityTimestampableTrait {
        EntityTimestampableTrait::__construct as private __TimestampableConstruct;
    }

    use EntityThreeStateStatusTrait;
    use EntityPublishableTrait {
        EntityPublishableTrait::__construct as private __PublishableConstruct;
    }

    /**
     * @var BaseMenuEntity | null
     */
    protected $menu;

    public function __construct()
    {
        $this->__TimestampableConstruct();
        $this->__PublishableConstruct();
    }

    /**
     * @return BaseMenuEntity|null
     */
    public function getMenu(): ?BaseMenuEntity
    {
        return $this->menu;
    }

    /**
     * @param BaseMenuEntity|null $menu
     */
    public function setMenu(?BaseMenuEntity $menu): void
    {
        $this->menu = $menu;
    }

    /**
     * @return string|null
     */
    public function getState(): string|ThreeStateStatusEnum|null
    {
        return $this->state;
    }

    /**
     * @param string|null $state
     */
    public function setState(string|ThreeStateStatusEnum|null $state): void
    {
        $this->state = $state;
    }

    /**
     * @ORM\PreRemove()
     */
    public function onRemove(LifecycleEventArgs $event): void
    {
        $this->setState(ThreeStateStatusEnum::UNPUBLISHED());
    }
}
