<?php

namespace Adeliom\EasyBlockBundle\Repository;

use Adeliom\EasyBlockBundle\Entity\BaseBlockEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;


class BaseBlockRepository extends ServiceEntityRepository {

    /**
     * @return QueryBuilder
     */
    public function getPublishedQuery(): QueryBuilder
    {
        $qb = $this->createQueryBuilder('block')
            ->where('block.status = :state')
        ;

        $qb->setParameter('state', true);
        return $qb;
    }

    /**
     * @return BaseBlockEntity[]
     */
    public function getActive()
    {
        $qb = $this->getPublishedQuery();
        return $qb->getQuery()
            ->getResult();
    }


    /**
     * @return BaseBlockEntity[]
     */
    public function getByType(string $type)
    {
        $qb = $this->getPublishedQuery();
        $qb->andWhere('block.type = :type')
            ->setParameter('type', $type);

        return $qb->getQuery()
            ->getResult();
    }
}
