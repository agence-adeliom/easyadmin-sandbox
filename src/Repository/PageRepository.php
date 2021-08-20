<?php

namespace App\Repository;

use Adeliom\EasyPageBundle\Repository\BasePageRepository;
use App\Entity\Article;
use App\Entity\Page;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @method Page|null find($id, $lockMode = null, $lockVersion = null)
 * @method Page|null findOneBy(array $criteria, array $orderBy = null)
 * @method Page[]    findAll()
 * @method Page[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageRepository extends BasePageRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Page::class);
    }
}
