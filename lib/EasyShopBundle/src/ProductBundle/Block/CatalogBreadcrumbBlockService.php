<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\ProductBundle\Block;

use Adeliom\EasyShop\BlockBundle\Block\BlockContextInterface;
use Adeliom\EasyShop\SeoBundle\Block\Breadcrumb\BaseBreadcrumbMenuBlockService;

/**
 * BlockService for product catalog breadcrumb.
 */
class CatalogBreadcrumbBlockService extends BaseBreadcrumbMenuBlockService
{
    public function getName()
    {
        return 'easy_shop.product.block.breadcrumb';
    }

    protected function getMenu(BlockContextInterface $blockContext)
    {
        $menu = $this->getRootMenu($blockContext);

        $menu->addChild('easy_shop_product_catalog_breadcrumb', [
            'route' => 'easy_shop_catalog_index',
            'extras' => ['translation_domain' => 'SonataProductBundle'],
        ]);

        $categories = [];
        $product = null;

        if ($category = $blockContext->getBlock()->getSetting('category')) {
            $sorted = [$category];

            while ($c = $category->getParent()) {
                $sorted[] = $c;
                $category = $c;
            }

            $categories = array_reverse($sorted, true);
        }

        if ($product = $blockContext->getBlock()->getSetting('product')) {
            if ($category = $product->getMainCategory()) {
                $sorted = [$category];

                while ($c = $category->getParent()) {
                    $sorted[] = $c;
                    $category = $c;
                }

                $category = null;

                $categories = array_reverse($sorted, true);
            }
        }

        if (\count($categories) > 0) {
            foreach ($categories as $category) {
                $menu->addChild($category->getName(), [
                    'route' => 'easy_shop_catalog_category',
                    'routeParameters' => [
                        'category_id' => $category->getId(),
                        'category_slug' => $category->getSlug(),
                    ],
                ]);
            }
        }

        if ($collection = $blockContext->getBlock()->getSetting('collection')) {
            $menu->addChild($collection->getName(), [
                    'route' => 'easy_shop_catalog_collection',
                    'routeParameters' => [
                        'collection_id' => $collection->getId(),
                        'collection_slug' => $collection->getSlug(),
                    ],
                ]);
        }

        if ($product) {
            $menu->addChild($product->getName(), [
                'route' => 'easy_shop_product_view',
                'routeParameters' => [
                    'productId' => $product->getId(),
                    'slug' => $product->getSlug(),
                ],
            ]);
        }

        return $menu;
    }
}
