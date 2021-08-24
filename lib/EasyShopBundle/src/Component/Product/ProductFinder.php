<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Product;


class ProductFinder implements ProductFinderInterface
{
    /**
     * @var ProductManagerInterface
     */
    private $pManager;

    public function __construct(ProductManagerInterface $pManager)
    {
        $this->pManager = $pManager;
    }

    public function getCrossSellingSimilarProducts(ProductInterface $product)
    {
        return $this->pManager->findInSameCollections($product->getProductCollections());
    }

    public function getCrossSellingSimilarParentProducts(ProductInterface $product, $limit = null)
    {
        return $this->pManager->findParentsInSameCollections($product->getProductCollections(), $limit);
    }

    public function getUpSellingSimilarProducts(ProductInterface $product): void
    {
        // TODO: Implement getUpSellingSimilarProducts() method.
    }
}
