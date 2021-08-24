<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\ProductBundle\Seo\Services;

use Adeliom\EasyShop\Component\Currency\CurrencyDetectorInterface;
use Adeliom\EasyShop\Component\Product\ProductInterface;
use Adeliom\EasyShop\IntlBundle\Templating\Helper\NumberHelper;
use Adeliom\EasyShop\MediaBundle\Provider\Pool;
use Adeliom\EasyShop\SeoBundle\Seo\SeoPageInterface;

/**
 * TwitterService.
 *
 */
class Twitter implements ServiceInterface
{
    /**
     * @var Pool
     */
    protected $mediaPool;

    /**
     * @var NumberHelper
     */
    protected $numberHelper;

    /**
     * @var CurrencyDetectorInterface
     */
    protected $currencyDetector;

    /**
     * @var string|null
     */
    protected $site;

    /**
     * @var string|null
     */
    protected $creator;

    /**
     * @var string|null
     */
    protected $domain;

    /**
     * @var string|null
     */
    protected $mediaFormat;

    /**
     * @param string $site
     * @param string $creator
     * @param string $domain
     * @param string $mediaFormat
     */
    public function __construct(Pool $mediaPool, NumberHelper $numberHelper, CurrencyDetectorInterface $currencyDetector, $site, $creator, $domain, $mediaFormat)
    {
        $this->mediaPool = $mediaPool;
        $this->numberHelper = $numberHelper;
        $this->currencyDetector = $currencyDetector;
        $this->site = $site;
        $this->creator = $creator;
        $this->domain = $domain;
        $this->mediaFormat = $mediaFormat;
    }

    /**
     * Add the meta information.
     */
    public function alterPage(SeoPageInterface $seoPage, ProductInterface $product): void
    {
        $seoPage->addMeta('name', 'twitter:card', 'product')
            ->addMeta('name', 'twitter:title', $product->getName())
            ->addMeta('name', 'twitter:description', substr((string) $product->getDescription(), 0, 200))
            ->addMeta('name', 'twitter:label1', 'Price')
            ->addMeta('name', 'twitter:data1', (string) $this->numberHelper->formatCurrency($product->getPrice(), $this->currencyDetector->getCurrency()->getLabel()))
            ->addMeta('name', 'twitter:label2', 'SKU')
            ->addMeta('name', 'twitter:data2', $product->getSku())
            ->addMeta('name', 'twitter:site', $this->site)
            ->addMeta('name', 'twitter:creator', $this->creator)
            ->addMeta('name', 'twitter:domain', $this->domain);

        if ($image = $product->getImage()) {
            $provider = $this->mediaPool->getProvider($image->getProviderName());
            $seoPage->addMeta('property', 'twitter:image:src', $this->domain.$provider->generatePublicUrl($image, $this->mediaFormat));
        }
    }
}
