<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Product;

use Adeliom\EasyShop\ClassificationBundle\Model\CategoryInterface;
use Adeliom\EasyShop\Doctrine\Model\ManagerInterface;

interface ProductCategoryManagerInterface extends ManagerInterface
{
    /**
     * Adds a Category to a Product.
     *
     * @param ProductInterface  $product  A Product entity
     * @param CategoryInterface $category A Category entity
     * @param bool              $main     Add as the main category?
     */
    public function addCategoryToProduct(ProductInterface $product, CategoryInterface $category, $main = false);

    /**
     * Removes a Category from a Product.
     */
    public function removeCategoryFromProduct(ProductInterface $product, CategoryInterface $category);

    /**
     * Gets the category tree.
     *
     * @return CategoryInterface[]
     */
    public function getCategoryTree();

    /**
     * Returns the number of products in $category (maxed by $limit).
     *
     * @param int $limit
     *
     * @return int
     */
    public function getProductCount(CategoryInterface $category, $limit = 1000);
}
