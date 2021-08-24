<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\ProductBundle\Model;

use Doctrine\Persistence\ManagerRegistry;
use Adeliom\EasyShop\Component\Product\Pool;
use Adeliom\EasyShop\DoctrineORMAdminBundle\Model\ModelManager;

/**
 * this method overwrite the default AdminModelManager to call
 * the custom methods from the dedicated media manager.
 */
class DoctrineModelManager extends ModelManager
{
    /**
     * @var Pool
     */
    protected $pool;

    public function __construct(ManagerRegistry $registry, Pool $pool)
    {
        parent::__construct($registry);

        $this->pool = $pool;
    }

    public function create($object): void
    {
        $this->pool->getManager($object)->save($object);
    }

    public function update($object): void
    {
        $this->pool->getManager($object)->save($object);
    }

    public function delete($object): void
    {
        $this->pool->getManager($object)->delete($object);
    }
}
