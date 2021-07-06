<?php

namespace App\Repository;

use App\Entity\EasyMediaMetas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EasyMediaMetas|null find($id, $lockMode = null, $lockVersion = null)
 * @method EasyMediaMetas|null findOneBy(array $criteria, array $orderBy = null)
 * @method EasyMediaMetas[]    findAll()
 * @method EasyMediaMetas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EasyMediaMetasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EasyMediaMetas::class);
    }

    // /**
    //  * @return EasyMediaMetas[] Returns an array of EasyMediaMetas objects
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
    public function findOneBySomeField($value): ?EasyMediaMetas
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
