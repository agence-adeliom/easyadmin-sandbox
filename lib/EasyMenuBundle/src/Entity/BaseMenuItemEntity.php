<?php

namespace Adeliom\EasyMenuBundle\Entity;

use Adeliom\EasyCommonBundle\Enum\ThreeStateStatusEnum;
use Adeliom\EasyCommonBundle\Traits\EntityIdTrait;
use Adeliom\EasyCommonBundle\Traits\EntityPublishableTrait;
use Adeliom\EasyCommonBundle\Traits\EntityThreeStateStatusTrait;
use Adeliom\EasyCommonBundle\Traits\EntityTimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Prodigious\Sonata\MenuBundle\Model\MenuItem;

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

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    protected $url;

    /**
     * @var string
     *
     * @ORM\Column(name="class_attribute", type="string", length=255, nullable=true)
     */
    protected $classAttribute;

    /**
     * @var integer
     *
     * @ORM\Column(name="position", type="smallint", options={"unsigned"=true}, nullable=true)
     */
    protected $position;

    /**
     * @var bool
     *
     * @ORM\Column(name="target", type="boolean", nullable=true, options={"default":false})
     */
    protected $target;

    /**
     * @var BaseMenuItemEntity | null
     */
    protected $parent;

    /**
     * @var BaseMenuItemEntity[]
     */
    protected $children;

    public function __construct()
    {
        $this->__TimestampableConstruct();
        $this->__PublishableConstruct();
        $this->children = new ArrayCollection();
        $this->position = 999;
        $this->state = ThreeStateStatusEnum::PENDING();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
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
     * @return BaseMenuItemEntity
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param BaseMenuItemEntity $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        if(!is_null($parent))
            $parent->addChild($this);
    }

    /**
     * Add child
     * @param BaseMenuItemEntity $child
     */
    public function addChild(BaseMenuItemEntity $child)
    {
        $this->children[] = $child;
    }

    /**
     * Remove child
     *
     * @param BaseMenuItemEntity $child
     */
    public function removeChild(BaseMenuItemEntity $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Set children
     * @param ArrayCollection $children
     */
    public function setChildren(ArrayCollection $children)
    {
        $this->children = $children;
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return string|ThreeStateStatusEnum|null
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string|ThreeStateStatusEnum|null $state
     */
    public function setState($state): void
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

    /**
     * Has child
     */
    public function hasChild()
    {
        return count($this->children) > 0;
    }

    /**
     * Has parent
     */
    public function hasParent()
    {
        return !is_null($this->parent);
    }

    public function getActiveChildren()
    {
        $children = array();

        foreach ($this->children as $child) {
            if($child->enabled) {
                array_push($children, $child);
            }
        }

        return $children;
    }

    public function __toString()
    {
        return isset($this->name) ? $this->name : "";
    }
}
