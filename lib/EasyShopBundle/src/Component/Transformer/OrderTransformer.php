<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Transformer;

use Adeliom\EasyShop\Component\Basket\BasketInterface;
use Adeliom\EasyShop\Component\Event\BasketTransformEvent;
use Adeliom\EasyShop\Component\Event\OrderTransformEvent;
use Adeliom\EasyShop\Component\Event\TransformerEvents;
use Adeliom\EasyShop\Component\Order\OrderElementInterface;
use Adeliom\EasyShop\Component\Order\OrderInterface;
use Adeliom\EasyShop\Component\Product\Pool as ProductPool;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class OrderTransformer extends BaseTransformer
{
    /**
     * @var ProductPool the product pool
     */
    protected $productPool;

    /**
     * @var array the transformer option
     */
    protected $options;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    public function __construct(ProductPool $productPool, EventDispatcherInterface $eventDispatcher)
    {
        $this->productPool = $productPool;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @return BasketInterface
     */
    public function transformIntoBasket(OrderInterface $order, BasketInterface $basket)
    {
        $event = new OrderTransformEvent($order);
        $this->eventDispatcher->dispatch($event, TransformerEvents::PRE_ORDER_TO_BASKET_TRANSFORM);

        // we reset the current basket
        $basket->reset(true);

        $basket->setCurrency($order->getCurrency());
        $basket->setLocale($order->getLocale());

        // We are free to convert !
        foreach ($order->getOrderElements() as $orderElement) {
            /*
             * @var $orderElement OrderElementInterface
             */
            $provider = $this->productPool->getProvider($orderElement->getProductType());
            $manager = $this->productPool->getManager($orderElement->getProductType());

            $product = $manager->findOneBy(['id' => $orderElement->getProductId()]);

            if (!$product) {
                continue;
            }

            $basketElement = $provider->createBasketElement($product, $orderElement->getOptions());
            $basketElement->setQuantity($orderElement->getQuantity());

            $provider->basketAddProduct($basket, $product, $basketElement);
        }

        $basket->setCustomer($order->getCustomer());

        $basket->buildPrices();

        $event = new BasketTransformEvent($basket);
        $this->eventDispatcher->dispatch($event, TransformerEvents::POST_ORDER_TO_BASKET_TRANSFORM);

        return $basket;
    }
}
