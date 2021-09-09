<?php

namespace App\Entity\Menu;

use Adeliom\EasyMenuBundle\Entity\BaseMenuEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="menus")
 */
class Menu extends BaseMenuEntity
{

}
