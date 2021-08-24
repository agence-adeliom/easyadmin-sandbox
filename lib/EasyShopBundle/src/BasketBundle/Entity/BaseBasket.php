<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\BasketBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Adeliom\EasyShop\Component\Basket\Basket;
use Adeliom\EasyShop\Component\Basket\BasketElementInterface;

abstract class BaseBasket extends Basket
{
    public function __construct()
    {
        $this->reset(true);
    }

    public function setBasketElements($basketElements): void
    {
        foreach ($basketElements as $basketElement) {
            if (!$basketElement instanceof BasketElementInterface) {
                continue;
            }

            $basketElement->setBasket($this);
            $this->addBasketElement($basketElement);
        }
    }

    public function reset($full = true): void
    {
        parent::reset($full);

        if ($full) {
            $this->basketElements = new ArrayCollection();
        }
    }
}
