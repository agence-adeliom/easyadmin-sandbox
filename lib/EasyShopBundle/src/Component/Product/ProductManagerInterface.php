<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Product;

use Adeliom\EasyShop\DatagridBundle\Pager\PageableInterface;
use Adeliom\EasyShop\Doctrine\Model\ManagerInterface;

interface ProductManagerInterface extends ManagerInterface, PageableInterface
{
    /**
     * Returns the products in the same collections as those specified in $productCollections.
     *
     * @param mixed $productCollections
     *
     * @return array
     */
    public function findInSameCollections($productCollections);

    /**
     * Returns the parent products in the same collections as those specified in $productCollections.
     *
     * @param mixed $productCollections
     *
     * @return array
     */
    public function findParentsInSameCollections($productCollections);

    /**
     * Retrieve an active product from its id and its slug.
     *
     * @param int    $id
     * @param string $slug
     *
     * @return ProductInterface|null
     */
    public function findEnabledFromIdAndSlug($id, $slug);

    /**
     * @return array
     */
    public function findVariations(ProductInterface $product);

    /**
     * Updated stock value for a given Product.
     *
     * @param ProductInterface|int $product
     * @param int                  $diff
     */
    public function updateStock($product, $diff);
}
