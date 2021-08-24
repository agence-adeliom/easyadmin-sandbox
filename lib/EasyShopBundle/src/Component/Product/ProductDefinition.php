<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Product;

class ProductDefinition
{
    /**
     * @var ProductManagerInterface
     */
    protected $manager;

    /**
     * @var ProductProviderInterface
     */
    protected $provider;

    public function __construct(ProductProviderInterface $provider, ProductManagerInterface $manager)
    {
        $this->provider = $provider;
        $this->manager = $manager;
    }

    /**
     * @return ProductManagerInterface
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @return ProductProviderInterface
     */
    public function getProvider()
    {
        return $this->provider;
    }
}
