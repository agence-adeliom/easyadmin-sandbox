<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\OrderBundle\Entity;

use Adeliom\EasyShop\Component\Order\OrderManagerInterface;
use Adeliom\EasyShop\DatagridBundle\Pager\Doctrine\Pager;
use Adeliom\EasyShop\DatagridBundle\Pager\PagerInterface;
use Adeliom\EasyShop\Doctrine\Entity\BaseEntityManager;
use Adeliom\EasyShop\UserBundle\Model\UserInterface;

class OrderManager extends BaseEntityManager implements OrderManagerInterface
{
    public function save($order, $andFlush = true): void
    {
        $this->getEntityManager()->persist($order->getCustomer());

        parent::save($order, $andFlush);
    }

    public function findForUser(UserInterface $user, array $orderBy = [], $limit = null, $offset = null)
    {
        $qb = $this->getRepository()->createQueryBuilder('o')
            ->leftJoin('o.customer', 'c')
            ->where('c.user = :user')
            ->setParameter('user', $user);

        foreach ($orderBy as $field => $dir) {
            $qb->orderBy('o.'.$field, $dir);
        }

        if (null !== $limit) {
            $qb->setMaxResults($limit);
        }

        if (null !== $offset) {
            $qb->setFirstResult($offset);
        }

        return $qb->getQuery()->execute();
    }

    public function getOrder($orderId)
    {
        $qb = $this->getRepository()->createQueryBuilder('o')
            ->select('o')
            ->innerJoin('o.orderElements', 'oe')
            ->where('o.id = :id')
            ->setParameter('id', $orderId);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getPager(array $criteria, int $page, int $limit = 10, array $sort = []): PagerInterface
    {
        $query = $this->getRepository()
            ->createQueryBuilder('o')
            ->select('o');

        $fields = $this->getEntityManager()->getClassMetadata($this->class)->getFieldNames();
        foreach ($sort as $field => $direction) {
            if (!\in_array($field, $fields, true)) {
                throw new \RuntimeException(sprintf("Invalid sort field '%s' in '%s' class", $field, $this->class));
            }
        }
        if (0 === \count($sort)) {
            $sort = ['reference' => 'ASC'];
        }
        foreach ($sort as $field => $direction) {
            $query->orderBy(sprintf('o.%s', $field), strtoupper($direction));
        }

        $parameters = [];

        if (isset($criteria['status'])) {
            $query->andWhere('o.status = :status');
            $parameters['status'] = $criteria['status'];
        }

        if (isset($criteria['customer'])) {
            $query->innerJoin('o.customer', 'c', 'WITH', 'c.id = :customer');
            $parameters['customer'] = $criteria['customer'];
        }

        $query->setParameters($parameters);

        return Pager::create($query, $limit, $page);
    }
}
