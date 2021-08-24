<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\CustomerBundle\Entity;

use Adeliom\EasyShop\Component\Customer\CustomerManagerInterface;
use Adeliom\EasyShop\DatagridBundle\Pager\Doctrine\Pager;
use Adeliom\EasyShop\DatagridBundle\Pager\PagerInterface;
use Adeliom\EasyShop\Doctrine\Entity\BaseEntityManager;

class CustomerManager extends BaseEntityManager implements CustomerManagerInterface
{
    public function getPager(array $criteria, int $page, int $limit = 10, array $sort = []): PagerInterface
    {
        $query = $this->getRepository()
            ->createQueryBuilder('c')
            ->select('c');

        $fields = $this->getEntityManager()->getClassMetadata($this->class)->getFieldNames();
        foreach ($sort as $field => $direction) {
            if (!\in_array($field, $fields, true)) {
                throw new \RuntimeException(sprintf("Invalid sort field '%s' in '%s' class", $field, $this->class));
            }
        }
        if (0 === \count($sort)) {
            $sort = ['lastname' => 'ASC'];
        }
        foreach ($sort as $field => $direction) {
            $query->orderBy(sprintf('c.%s', $field), strtoupper($direction));
        }

        $parameters = [];

        if (isset($criteria['is_fake'])) {
            $query->andWhere('c.isFake = :isFake');
            $parameters['isFake'] = $criteria['is_fake'];
        }

        $query->setParameters($parameters);

        return Pager::create($query, $limit, $page);
    }
}
