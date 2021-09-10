<?php

namespace App\Entity\Menu;

use Adeliom\EasyMenuBundle\Entity\BaseMenuEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Menu\MenuRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="menus")
 */
class Menu extends BaseMenuEntity
{

}
