<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Product;

use Adeliom\EasyShop\ClassificationBundle\Model\CategoryInterface;

interface ProductCategoryInterface
{
    /**
     * Set enabled.
     *
     * @param bool $enabled
     */
    public function setEnabled($enabled);

    /**
     * Get enabled.
     *
     * @return bool $enabled
     */
    public function getEnabled();

    /**
     * Set if product category is the main category.
     *
     * @param bool $main
     */
    public function setMain($main);

    /**
     * Get if product category is the main category.
     *
     * @return bool $main
     */
    public function getMain();

    /**
     * Set updatedAt.
     *
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(?\DateTime $updatedAt = null);

    /**
     * Get updatedAt.
     *
     * @return \DateTime $updatedAt
     */
    public function getUpdatedAt();

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(?\DateTime $createdAt = null);

    /**
     * Get createdAt.
     *
     * @return \Datetime $createdAt
     */
    public function getCreatedAt();

    /**
     * Set Product.
     */
    public function setProduct(ProductInterface $product);

    /**
     * Get Product.
     *
     * @return ProductInterface
     */
    public function getProduct();

    /**
     * Set Category.
     */
    public function setCategory(CategoryInterface $category);

    /**
     * Get Category.
     *
     * @return CategoryInterface $category
     */
    public function getCategory();
}
