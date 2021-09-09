<?php

namespace App\Entity\Menu;

use Adeliom\EasyMenuBundle\Entity\BaseMenuItemEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="menus_items")
 */
class MenuItem extends BaseMenuItemEntity
{

}
