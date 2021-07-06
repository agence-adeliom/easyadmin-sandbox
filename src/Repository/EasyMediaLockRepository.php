<?php

namespace App\Repository;

use App\Entity\EasyMediaLock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EasyMediaLock|null find($id, $lockMode = null, $lockVersion = null)
 * @method EasyMediaLock|null findOneBy(array $criteria, array $orderBy = null)
 * @method EasyMediaLock[]    findAll()
 * @method EasyMediaLock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EasyMediaLockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EasyMediaLock::class);
    }

    // /**
    //  * @return EasyMediaLock[] Returns an array of EasyMediaLock objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EasyMediaLock
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
