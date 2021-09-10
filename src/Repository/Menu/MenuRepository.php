<?php

namespace App\Repository\Menu;

use Adeliom\EasyMenuBundle\Repository\BaseMenuRepository;
use App\Entity\Menu\Menu;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @method Menu|null find($id, $lockMode = null, $lockVersion = null)
 * @method Menu|null findOneBy(array $criteria, array $orderBy = null)
 * @method Menu[]    findAll()
 * @method Menu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenuRepository extends BaseMenuRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Menu::class);
    }
}
