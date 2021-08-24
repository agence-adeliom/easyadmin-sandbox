<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Basket;

use Doctrine\ORM\NoResultException;
use Adeliom\EasyShop\Component\Customer\CustomerInterface;
use Adeliom\EasyShop\DatagridBundle\Pager\Doctrine\Pager;
use Adeliom\EasyShop\DatagridBundle\Pager\PagerInterface;
use Adeliom\EasyShop\Doctrine\Entity\BaseEntityManager;

class BasketManager extends BaseEntityManager implements BasketManagerInterface
{
    public function loadBasketPerCustomer(CustomerInterface $customer)
    {
        try {
            return $this->getRepository()->createQueryBuilder('b')
                ->leftJoin('b.basketElements', 'be', null, null, 'be.position')
                ->where('b.customer = :customer')
                ->setParameter('customer', $customer->getId())
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException $e) {
            return;
        }
    }

    public function save($entity, $andFlush = true): void
    {
        foreach ($entity->getBasketElements() as $element) {
            $element->setBasket($entity);
        }

        parent::save($entity, $andFlush);
    }

    public function getPager(array $criteria, int $page, int $limit = 10, array $sort = []): PagerInterface
    {
        $query = $this->getRepository()
            ->createQueryBuilder('b')
            ->select('b');

        $fields = $this->getEntityManager()->getClassMetadata($this->class)->getFieldNames();
        foreach ($sort as $field => $direction) {
            if (!\in_array($field, $fields, true)) {
                throw new \RuntimeException(sprintf("Invalid sort field '%s' in '%s' class", $field, $this->class));
            }
        }
        if (0 === \count($sort)) {
            $sort = ['id' => 'ASC'];
        }
        foreach ($sort as $field => $direction) {
            $query->orderBy(sprintf('b.%s', $field), strtoupper($direction));
        }

        return Pager::create($query, $limit, $page);
    }
}
