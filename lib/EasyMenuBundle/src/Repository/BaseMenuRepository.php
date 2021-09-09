<?php

namespace Adeliom\EasyMenuBundle\Repository;

use Adeliom\EasyMenuBundle\Entity\BaseMenuEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;


class BaseMenuRepository extends ServiceEntityRepository {

    /**
     * @var bool
     */
    protected $cacheEnabled = false;

    /**
     * @var int
     */
    protected $cacheTtl;

    /**
     * @param array $cacheConfig
     */
    public function setConfig(array $cacheConfig)
    {
        $this->cacheEnabled = $cacheConfig['enabled'];
        $this->cacheTtl     = $cacheConfig['ttl'];
    }

    /**
     * @return QueryBuilder
     */
    public function getPublishedQuery(): QueryBuilder
    {
        $qb = $this->createQueryBuilder('menu')
            ->where('menu.status = :status')
        ;

        $qb->setParameter('status', true);
        return $qb;
    }

    /**
     * @return BaseMenuEntity[]
     */
    public function getPublished()
    {
        $qb = $this->getPublishedQuery();
        return $qb->getQuery()
            ->useResultCache($this->cacheEnabled, $this->cacheTtl)
            ->getResult();
    }

}
