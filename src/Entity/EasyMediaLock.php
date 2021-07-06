<?php

namespace App\Entity;

use Adeliom\EasyMediaBundle\Entity\BaseEasyMediaLock;
use App\Repository\EasyMediaLockRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EasyMediaLockRepository::class)
 */
class EasyMediaLock extends BaseEasyMediaLock
{

}
