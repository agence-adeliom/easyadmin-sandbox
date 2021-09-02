<?php

namespace App\Repository\Faq;

use Adeliom\EasyFaqBundle\Repository\BaseCategoryRepository;
use App\Entity\Faq\Category;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends BaseCategoryRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }
}
