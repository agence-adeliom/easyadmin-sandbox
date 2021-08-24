<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\ProductBundle\Entity;

use Adeliom\EasyShop\ClassificationBundle\Model\CollectionInterface;
use Adeliom\EasyShop\Component\Product\ProductCollectionInterface;
use Adeliom\EasyShop\Component\Product\ProductInterface;

abstract class BaseProductCollection implements ProductCollectionInterface
{
    /**
     * @var bool
     */
    protected $enabled;

    /**
     * @var \DateTime
     */
    protected $updatedAt;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var ProductInterface
     */
    protected $product;

    /**
     * @var CollectionInterface
     */
    protected $collection;

    public function __toString()
    {
        return ($this->getProduct() ? $this->getProduct()->getName() : 'null').' - '.($this->getCollection() ? $this->getCollection()->getName() : 'null');
    }

    public function setEnabled($enabled): void
    {
        $this->enabled = $enabled;
    }

    public function getEnabled()
    {
        return $this->enabled;
    }

    public function setUpdatedAt(?\DateTime $updatedAt = null): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setCreatedAt(?\DateTime $createdAt = null): void
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setProduct(ProductInterface $product): void
    {
        $this->product = $product;
    }

    public function getProduct()
    {
        return $this->product;
    }

    public function setCollection(CollectionInterface $collection): void
    {
        $this->collection = $collection;
    }

    public function getCollection()
    {
        return $this->collection;
    }
}
