<?php

namespace App\Entity\Faq;

use Adeliom\EasyFaqBundle\Entity\BaseCategoryEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Faq\CategoryRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Category extends BaseCategoryEntity
{

}
