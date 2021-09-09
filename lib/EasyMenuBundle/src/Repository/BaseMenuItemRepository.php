<?php

namespace Adeliom\EasyMenuBundle\Repository;

use Adeliom\EasyFaqBundle\Entity\BaseCategoryEntity;
use Adeliom\EasyFaqBundle\Entity\BaseEntryEntity;
use Adeliom\EasyCommonBundle\Enum\ThreeStateStatusEnum;
use Adeliom\EasyMenuBundle\Entity\BaseMenuEntity;
use Adeliom\EasyMenuBundle\Entity\BaseMenuItemEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;


class BaseMenuItemRepository extends ServiceEntityRepository {

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
        $qb = $this->createQueryBuilder('menuitem')
            ->where('menuitem.state = :state')
            ->andWhere('menuitem.publishDate < :publishDate')
        ;

        $orModule = $qb->expr()->orx();
        $orModule->add($qb->expr()->gt('menuitem.unpublishDate', ':unpublishDate'));
        $orModule->add($qb->expr()->isNull('menuitem.unpublishDate'));

        $qb->andWhere($orModule);

        $qb->setParameter('state', ThreeStateStatusEnum::PUBLISHED());
        $qb->setParameter('publishDate', new \DateTime());
        $qb->setParameter('unpublishDate', new \DateTime());

        return $qb;
    }

    /**
     * @return BaseEntryEntity[]
     */
    public function getPublished(bool $returnQueryBuilder = false)
    {
        $qb = $this->getPublishedQuery();
        if ($returnQueryBuilder){
            return $qb;
        }
        return $qb->getQuery()
            ->useResultCache($this->cacheEnabled, $this->cacheTtl)
            ->getResult();
    }

    /**
     * @return BaseMenuItemEntity[]
     */
    public function getByMenu(BaseMenuEntity $menuEntity, bool $returnQueryBuilder = false)
    {
        $qb = $this->getPublishedQuery();
        $qb->andWhere('menuitem.menu = :menu')
            ->setParameter('menu', $menuEntity)
        ;
        if ($returnQueryBuilder){
            return $qb;
        }
        return $qb->getQuery()
            ->useResultCache($this->cacheEnabled, $this->cacheTtl)
            ->getResult();
    }

}
