<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Product;

use Adeliom\EasyShop\ClassificationBundle\Model\CollectionInterface;
use Adeliom\EasyShop\Doctrine\Model\ManagerInterface;

interface ProductCollectionManagerInterface extends ManagerInterface
{
    /**
     * Adds a Category to a Product.
     */
    public function addCollectionToProduct(ProductInterface $product, CollectionInterface $collection);

    /**
     * Removes a Category from a Product.
     */
    public function removeCollectionFromProduct(ProductInterface $product, CollectionInterface $collection);
}
