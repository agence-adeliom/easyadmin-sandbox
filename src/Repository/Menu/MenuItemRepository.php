<?php

namespace App\Repository\Menu;

use Adeliom\EasyMenuBundle\Repository\BaseMenuItemRepository;
use App\Entity\Menu\MenuItem;
use Doctrine\ORM\EntityManager;


/**
 * @method MenuItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method MenuItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method MenuItem[]    findAll()
 * @method MenuItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenuItemRepository extends BaseMenuItemRepository
{
    public function __construct(EntityManager $entityManager)
    {
        parent::__construct($entityManager, $entityManager->getClassMetadata(MenuItem::class));
    }
}
