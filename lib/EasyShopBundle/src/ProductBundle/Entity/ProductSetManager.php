<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\ProductBundle\Entity;

use Adeliom\EasyShop\Component\Product\ProductInterface;

class ProductSetManager extends ProductManager
{
    /**
     * Deletes a product.
     *
     * @param ProductInterface $productSet
     * @param bool             $andFlush
     *
     * @throws \RuntimeException
     */
    public function delete($productSet, $andFlush = true): void
    {
        throw new \RuntimeException('A ProductSetManager cannot delete a product');
    }

    /**
     * Creates an empty ProductSet instance.
     *
     * @throws \RuntimeException
     */
    public function create(): void
    {
        throw new \RuntimeException('A ProductSetManager cannot create a product');
    }

    /**
     * Creates an empty ProductSet instance.
     *
     * @param ProductInterface $productSet
     * @param bool             $andFlush
     *
     * @throws \RuntimeException
     */
    public function save($productSet, $andFlush = true): void
    {
        throw new \RuntimeException('A ProductSetManager cannot save a product');
    }
}
