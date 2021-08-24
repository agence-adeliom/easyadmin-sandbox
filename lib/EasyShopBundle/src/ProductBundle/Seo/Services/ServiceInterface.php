<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\ProductBundle\Seo\Services;

use Adeliom\EasyShop\Component\Product\ProductInterface;
use Adeliom\EasyShop\SeoBundle\Seo\SeoPageInterface;

/**
 * ServiceInterface.
 *
 */
interface ServiceInterface
{
    /**
     * Add the meta information.
     */
    public function alterPage(SeoPageInterface $seoPage, ProductInterface $product);
}
