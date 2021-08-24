<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Product;


interface ProductFinderInterface
{
    /**
     * Gets similar product as $product in a cross selling fashion.
     *
     * @return ProductInterface[]
     */
    public function getCrossSellingSimilarProducts(ProductInterface $product);

    /**
     * Gets similar parent products as $product in a cross selling fashion.
     *
     * @return ProductInterface[]
     */
    public function getCrossSellingSimilarParentProducts(ProductInterface $product);

    /**
     * Gets similar product as $product in an up selling fashion.
     *
     * @return ProductInterface[]
     */
    public function getUpSellingSimilarProducts(ProductInterface $product);
}
