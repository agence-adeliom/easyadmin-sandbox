<?php

namespace App\Entity\EasyFaq;

use Adeliom\EasyFaqBundle\Entity\CategoryEntity as BaseCategoryEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EasyFaq\CategoryRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="faq_categories")
 */
class Category extends BaseCategoryEntity
{

}
