<?php

namespace App\Repository\Menu;

use Adeliom\EasyMenuBundle\Repository\BaseMenuItemRepository;
use App\Entity\Menu\MenuItem;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @method MenuItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method MenuItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method MenuItem[]    findAll()
 * @method MenuItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenuItemRepository extends BaseMenuItemRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MenuItem::class);
    }
}
