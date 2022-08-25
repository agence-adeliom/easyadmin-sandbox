<?php

namespace App\Entity\EasyBlog;

use Adeliom\EasyBlogBundle\Entity\CategoryEntity as BaseCategoryEntity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: \App\Repository\EasyBlog\CategoryRepository::class)]
#[ORM\Table(name: 'easy_post__category')]
#[ORM\HasLifecycleCallbacks]
class Category extends BaseCategoryEntity
{
}
