<?php

namespace Adeliom\EasyFaqBundle\Repository;

use Adeliom\EasyFaqBundle\Entity\BaseCategoryEntity;
use Adeliom\EasyFaqBundle\Entity\BaseEntryEntity;
use Adeliom\EasyCommonBundle\Enum\ThreeStateStatusEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;


class BaseEntryRepository extends ServiceEntityRepository {

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
        $qb = $this->createQueryBuilder('entry')
            ->innerJoin('entry.category', "category")
            ->where('entry.state = :state')
            ->andWhere('entry.publishDate < :publishDate')
            ->andWhere('category.status = :categoryActive')
        ;

        $orModule = $qb->expr()->orx();
        $orModule->add($qb->expr()->gt('entry.unpublishDate', ':unpublishDate'));
        $orModule->add($qb->expr()->isNull('entry.unpublishDate'));

        $qb->andWhere($orModule);


        $qb->setParameter('categoryActive', true);
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
     * @return BaseEntryEntity[]
     */
    public function getByCategory(BaseCategoryEntity $categoryEntity, bool $returnQueryBuilder = false)
    {
        $qb = $this->getPublishedQuery();
        $qb->andWhere('entry.category = :category')
            ->setParameter('category', $categoryEntity)
        ;
        if ($returnQueryBuilder){
            return $qb;
        }
        return $qb->getQuery()
            ->useResultCache($this->cacheEnabled, $this->cacheTtl)
            ->getResult();
    }

    /**
     * @return BaseEntryEntity
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getBySlug(string $slug, ?BaseCategoryEntity $categoryEntity, bool $returnQueryBuilder = false)
    {
        $qb = $this->getPublishedQuery();
        $qb->andWhere('entry.slug = :slug')
            ->setParameter('slug', $slug);
        if ($categoryEntity) {
            $qb->andWhere('entry.category = :category')
                ->setParameter('category', $categoryEntity);
        }
        $qb->setMaxResults(1);
        if ($returnQueryBuilder){
            return $qb;
        }
        return $qb->getQuery()
            ->useResultCache($this->cacheEnabled, $this->cacheTtl)
            ->getOneOrNullResult();
    }

}
