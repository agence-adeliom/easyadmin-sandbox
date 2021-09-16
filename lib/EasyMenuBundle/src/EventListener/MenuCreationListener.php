<?php

namespace Adeliom\EasyMenuBundle\EventListener;


use Adeliom\EasyCommonBundle\Enum\ThreeStateStatusEnum;
use Adeliom\EasyMenuBundle\Entity\BaseMenuEntity;
use Adeliom\EasyMenuBundle\Entity\BaseMenuItemEntity;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class MenuCreationListener
{
    protected $menuClass;
    protected $menuItemClass;

    public function __construct(string $menuClass, string $menuItemClass)
    {
        $this->menuClass = $menuClass;
        $this->menuItemClass = $menuItemClass;
    }

    // the entity listener methods receive two arguments:
    // the entity instance and the lifecycle event
    public function prePersist(BaseMenuEntity $menu, LifecycleEventArgs $event): void
    {
        /**
         * @var BaseMenuItemEntity $rootItem
         */
        $rootItem = new $this->menuItemClass();
        $rootItem->setMenu($menu);
        $rootItem->setName('Root');
        $rootItem->setState(ThreeStateStatusEnum::PUBLISHED());

        $menu->addItem($rootItem);
    }
}
