<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Basket;

use Adeliom\EasyShop\Component\Customer\CustomerSelectorInterface;

class Loader
{
    /**
     * @var \Adeliom\EasyShop\Component\Basket\BasketFactoryInterface
     */
    protected $basketFactory;

    /**
     * @var \Adeliom\EasyShop\Component\Customer\CustomerSelectorInterface
     */
    protected $customerSelector;

    /**
     * @var \Adeliom\EasyShop\Component\Basket\BasketInterface
     */
    protected $basket;

    /**
     * @param \Adeliom\EasyShop\Component\Basket\BasketFactoryInterface $basketFactory
     */
    public function __construct(BasketFactoryInterface $basketFactory, CustomerSelectorInterface $customerSelector)
    {
        $this->basketFactory = $basketFactory;
        $this->customerSelector = $customerSelector;
    }

    /**
     * Get the basket.
     *
     * @throws \Exception|\RuntimeException
     *
     * @return \Adeliom\EasyShop\Component\Basket\BasketInterface
     */
    public function getBasket()
    {
        if (!$this->basket) {
            try {
                $this->basket = $this->basketFactory->load($this->customerSelector->get());
            } catch (\Exception $e) {
                // something went wrong while loading the basket
                throw $e;
            }
        }

        return $this->basket;
    }
}
