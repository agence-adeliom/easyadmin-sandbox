<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\ProductBundle\Entity;

use Adeliom\EasyShop\ClassificationBundle\Model\CategoryInterface;
use Adeliom\EasyShop\Component\Product\ProductCategoryManagerInterface;
use Adeliom\EasyShop\Component\Product\ProductInterface;
use Adeliom\EasyShop\Doctrine\Entity\BaseEntityManager;

class ProductCategoryManager extends BaseEntityManager implements ProductCategoryManagerInterface
{
    //    const CATEGORY_PRODUCT_TYPE = 'product';

    /**
     * @var \Adeliom\EasyShop\ClassificationBundle\Model\CategoryManagerInterface
     */
    protected $categoryManager;

    public function addCategoryToProduct(ProductInterface $product, CategoryInterface $category, $main = false): void
    {
        if ($this->findOneBy(['category' => $category, 'product' => $product])) {
            return;
        }
        //
        //        if (null !== $category->getType() && self::CATEGORY_PRODUCT_TYPE !== $category->getType()) {
        //            // Should we throw an exception instead?
        //            $category->setType(self::CATEGORY_PRODUCT_TYPE);
        //            $this->categoryManager->save($category);
        //        }

        $productCategory = $this->create();

        $productCategory->setProduct($product);
        $productCategory->setCategory($category);
        $productCategory->setEnabled(true);
        $productCategory->setMain($main);

        $product->addProductCategory($productCategory);

        $this->save($productCategory);
    }

    public function removeCategoryFromProduct(ProductInterface $product, CategoryInterface $category): void
    {
        if (!$productCategory = $this->findOneBy(['category' => $category, 'product' => $product])) {
            return;
        }

        $product->removeProductCategory($productCategory);

        $this->delete($productCategory);
    }

    public function getCategoryTree()
    {
        $qb = $this->getRepository()->createQueryBuilder('pc')
            ->select('c, pc')
            ->leftJoin('pc.category', 'c')
            ->where('pc.enabled = true')
            ->andWhere('c.enabled = true');

        $pCategories = $qb->getQuery()->execute();

        $categoryTree = [];

        foreach ($pCategories as $category) {
            $this->putInTree($category->getCategory(), $categoryTree);
        }

        return $categoryTree;
    }

    public function getProductCount(CategoryInterface $category, $limit = 1000)
    {
        // Can't perform limit in subqueries with Doctrine... Hence raw SQL
        $metadata = $this->getEntityManager()->getClassMetadata($this->class);
        $productMetadata = $this->getEntityManager()->getClassMetadata($metadata->getAssociationTargetClass('product'));
        $categoryMetadata = $this->getEntityManager()->getClassMetadata($metadata->getAssociationTargetClass('category'));

        $sql = 'SELECT count(cnt.product_id) AS "cntId"
            FROM (
                SELECT DISTINCT pc.product_id
                FROM %s pc
                LEFT JOIN %s p ON pc.product_id = p.id
                LEFT JOIN %s c ON pc.category_id = c.id
                LEFT JOIN %s p2 ON p.id = p2.parent_id
                WHERE p.enabled = :productEnabled
                AND (p2.enabled = :parentEnabled OR p2.enabled IS NULL)
                AND (c.enabled = :categoryEnabled OR c.enabled IS NULL)
                AND p.parent_id IS NULL
                AND pc.category_id = :categoryId
                LIMIT %d
                ) AS cnt';

        $sql = sprintf($sql, $metadata->table['name'], $productMetadata->table['name'], $categoryMetadata->table['name'], $productMetadata->table['name'], $limit);

        $statement = $this->getConnection()->prepare($sql);
        $statement->bindValue('productEnabled', 1);
        $statement->bindValue('parentEnabled', 1);
        $statement->bindValue('categoryEnabled', 1);
        $statement->bindValue('categoryId', $category->getId());

        $statement->execute();
        $res = $statement->fetchAll();

        return $res[0]['cntId'];
    }

    /**
     * Finds $category place in $tree.
     */
    protected function putInTree(CategoryInterface $category, array &$tree): void
    {
        if (null === $category->getParent()) {
            $tree[$category->getId()] = $category;
        } else {
            $this->putInTree($category->getParent(), $tree);
        }
    }
}
