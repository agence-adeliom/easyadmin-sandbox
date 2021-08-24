<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\InvoiceBundle\Entity;

use Adeliom\EasyShop\Component\Invoice\InvoiceManagerInterface;
use Adeliom\EasyShop\DatagridBundle\Pager\Doctrine\Pager;
use Adeliom\EasyShop\DatagridBundle\Pager\PagerInterface;
use Adeliom\EasyShop\Doctrine\Entity\BaseEntityManager;

class InvoiceManager extends BaseEntityManager implements InvoiceManagerInterface
{
    public function getPager(array $criteria, int $page, int $limit = 10, array $sort = []): PagerInterface
    {
        $query = $this->getRepository()
            ->createQueryBuilder('i')
            ->select('i');

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
            $query->orderBy(sprintf('i.%s', $field), strtoupper($direction));
        }

        $parameters = [];

        if (isset($criteria['status'])) {
            $query->andWhere('i.status = :status');
            $parameters['status'] = $criteria['status'];
        }

        $query->setParameters($parameters);
        $pager = new Pager();

        return Pager::create($query, $limit, $page);
    }
}
