<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\ProductBundle\Entity;

use Adeliom\EasyShop\ClassificationBundle\Model\CollectionInterface;
use Adeliom\EasyShop\Component\Product\ProductCollectionManagerInterface;
use Adeliom\EasyShop\Component\Product\ProductInterface;
use Adeliom\EasyShop\Doctrine\Entity\BaseEntityManager;

class ProductCollectionManager extends BaseEntityManager implements ProductCollectionManagerInterface
{
    public function addCollectionToProduct(ProductInterface $product, CollectionInterface $collection): void
    {
        if ($this->findOneBy(['collection' => $collection, 'product' => $product])) {
            return;
        }

        $productCollection = $this->create();

        $productCollection->setProduct($product);
        $productCollection->setCollection($collection);
        $productCollection->setEnabled(true);

        $product->addProductCollection($productCollection);

        $this->save($productCollection);
    }

    public function removeCollectionFromProduct(ProductInterface $product, CollectionInterface $collection): void
    {
        if (!$productCollection = $this->findOneBy(['collection' => $collection, 'product' => $product])) {
            return;
        }

        $product->removeProductCollection($productCollection);

        $this->delete($productCollection);
    }
}
