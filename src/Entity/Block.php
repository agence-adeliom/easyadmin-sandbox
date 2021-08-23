<?php

namespace App\Entity;

use Adeliom\EasyBlockBundle\Entity\BaseBlockEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BlockRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Block extends BaseBlockEntity
{

}
