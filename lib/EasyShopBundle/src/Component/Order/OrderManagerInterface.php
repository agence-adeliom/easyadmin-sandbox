<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Order;

use Adeliom\EasyShop\DatagridBundle\Pager\PageableInterface;
use Adeliom\EasyShop\Doctrine\Model\ManagerInterface;
use Adeliom\EasyShop\UserBundle\Model\UserInterface;

interface OrderManagerInterface extends ManagerInterface, PageableInterface
{
    /**
     * Finds orders belonging to given user.
     *
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return OrderInterface[]
     */
    public function findForUser(UserInterface $user, array $orderBy = [], $limit = null, $offset = null);

    /**
     * Return an Order from its id with its related OrderElements.
     *
     * @param int $orderId
     *
     * @return OrderInterface
     */
    public function getOrder($orderId);
}
