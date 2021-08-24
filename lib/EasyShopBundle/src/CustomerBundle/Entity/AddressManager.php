<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\CustomerBundle\Entity;

use Adeliom\EasyShop\Component\Customer\AddressInterface;
use Adeliom\EasyShop\Component\Customer\AddressManagerInterface;
use Adeliom\EasyShop\DatagridBundle\Pager\Doctrine\Pager;
use Adeliom\EasyShop\DatagridBundle\Pager\PagerInterface;
use Adeliom\EasyShop\Doctrine\Entity\BaseEntityManager;

class AddressManager extends BaseEntityManager implements AddressManagerInterface
{
    public function setCurrent(AddressInterface $address): void
    {
        foreach ($address->getCustomer()->getAddressesByType($address->getType()) as $custAddress) {
            if ($custAddress->getCurrent()) {
                $custAddress->setCurrent(false);
                $this->save($custAddress);

                break;
            }
        }

        $address->setCurrent(true);
        $this->save($address);
    }

    public function delete($address, $andFlush = true): void
    {
        if ($address->getCurrent()) {
            $custAddresses = $address->getCustomer()->getAddressesByType(AddressInterface::TYPE_DELIVERY);

            if (\count($custAddresses) > 1) {
                foreach ($custAddresses as $currentAddress) {
                    if ($currentAddress->getId() !== $address->getId()) {
                        $currentAddress->setCurrent(true);
                        $this->save($currentAddress);

                        break;
                    }
                }
            }
        }

        parent::delete($address, $andFlush);
    }

    public function getPager(array $criteria, int $page, int $limit = 10, array $sort = []): PagerInterface
    {
        $query = $this->getRepository()
            ->createQueryBuilder('a')
            ->select('a');

        $fields = $this->getEntityManager()->getClassMetadata($this->class)->getFieldNames();
        foreach ($sort as $field => $direction) {
            if (!\in_array($field, $fields, true)) {
                throw new \RuntimeException(sprintf("Invalid sort field '%s' in '%s' class", $field, $this->class));
            }
        }
        if (0 === \count($sort)) {
            $sort = ['name' => 'ASC'];
        }
        foreach ($sort as $field => $direction) {
            $query->orderBy(sprintf('a.%s', $field), strtoupper($direction));
        }

        $parameters = [];

        if (isset($criteria['customer'])) {
            $query->innerJoin('a.customer', 'c', 'WITH', 'c.id = :customer');
            $parameters['customer'] = $criteria['customer'];
        }

        $query->setParameters($parameters);

        return Pager::create($query, $limit, $page);
    }
}
