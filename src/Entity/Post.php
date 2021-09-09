<?php

namespace App\Entity;

use Adeliom\EasyBlogBundle\Entity\BasePostEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Post extends BasePostEntity
{

}
