<?php

namespace App\Entity;

use Adeliom\EasyMediaBundle\Entity\BaseEasyMediaMetas;
use App\Repository\EasyMediaMetasRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EasyMediaMetasRepository::class)
 */
class EasyMediaMetas extends BaseEasyMediaMetas
{

}
