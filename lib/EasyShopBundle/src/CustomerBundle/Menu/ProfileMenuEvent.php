<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\CustomerBundle\Menu;

use Knp\Menu\ItemInterface;
use Symfony\Contracts\EventDispatcher\Event;


final class ProfileMenuEvent extends Event
{
    /**
     * @var ItemInterface
     */
    private $menu;

    public function __construct(ItemInterface $menu)
    {
        $this->menu = $menu;
    }

    public function getMenu(): ItemInterface
    {
        return $this->menu;
    }
}
