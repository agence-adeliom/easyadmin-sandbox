<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\OrderBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Adeliom\EasyShop\Component\Order\OrderInterface;
use Adeliom\EasyShop\Component\Order\OrderManagerInterface;
use Adeliom\EasyShop\DatagridBundle\Pager\PagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class OrderController
{
    /**
     * @var \Adeliom\EasyShop\Component\Order\OrderManagerInterface
     */
    protected $orderManager;

    public function __construct(OrderManagerInterface $orderManager)
    {
        $this->orderManager = $orderManager;
    }

    /**
     * Returns a paginated list of orders.
     *
     * @ApiDoc(
     *  resource=true,
     *  output={"class"="Adeliom\EasyShop\DatagridBundle\Pager\PagerInterface", "groups"={"easy_shop_api_read"}}
     * )
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="Page for orders list pagination (1-indexed)")
     * @Rest\QueryParam(name="count", requirements="\d+", default="10", description="Number of orders by page")
     * @Rest\QueryParam(name="orderBy", map=true, requirements="ASC|DESC", nullable=true, strict=true, description="Sort specification for the resultset (key is field, value is direction")
     * @Rest\QueryParam(name="status", requirements="\d+", nullable=true, strict=true, description="Filter on order statuses")
     * @Rest\QueryParam(name="customer", requirements="\d+", nullable=true, strict=true, description="Filter on customer id")
     *
     * @Rest\View(serializerGroups={"easy_shop_api_read"}, serializerEnableMaxDepthChecks=true)
     *
     * @return PagerInterface
     */
    public function getOrdersAction(ParamFetcherInterface $paramFetcher)
    {
        $supportedCriteria = [
            'status' => '',
            'customer' => '',
        ];

        $page = $paramFetcher->get('page');
        $limit = $paramFetcher->get('count');
        $sort = $paramFetcher->get('orderBy');
        $criteria = array_intersect_key($paramFetcher->all(), $supportedCriteria);

        foreach ($criteria as $key => $value) {
            if (null === $value) {
                unset($criteria[$key]);
            }
        }

        if (!$sort) {
            $sort = [];
        } elseif (!\is_array($sort)) {
            $sort = [$sort => 'asc'];
        }

        return $this->orderManager->getPager($criteria, $page, $limit, $sort);
    }

    /**
     * Retrieves a specific order.
     *
     * @ApiDoc(
     *  requirements={
     *      {"name"="id", "dataType"="string", "description"="Order identifier"}
     *  },
     *  output={"class"="Adeliom\EasyShop\Component\Order\OrderInterface", "groups"={"easy_shop_api_read"}},
     *  statusCodes={
     *      200="Returned when successful",
     *      404="Returned when order is not found"
     *  }
     * )
     *
     * @Rest\View(serializerGroups={"easy_shop_api_read"}, serializerEnableMaxDepthChecks=true)
     *
     * @param string $id Order identifier
     *
     * @return OrderInterface
     */
    public function getOrderAction($id)
    {
        return $this->getOrder($id);
    }

    /**
     * Retrieves a specific order's elements.
     *
     * @ApiDoc(
     *  requirements={
     *      {"name"="id", "dataType"="string", "description"="Order identifier"}
     *  },
     *  output={"class"="Adeliom\EasyShop\Component\Order\OrderElementInterface", "groups"={"easy_shop_api_read"}},
     *  statusCodes={
     *      200="Returned when successful",
     *      404="Returned when order is not found"
     *  }
     * )
     *
     * @Rest\View(serializerGroups={"easy_shop_api_read"}, serializerEnableMaxDepthChecks=true)
     *
     * @param string $id Order identifier
     *
     * @return array
     */
    public function getOrderOrderelementsAction($id)
    {
        return $this->getOrder($id)->getOrderElements();
    }

    /**
     * Retrieves order with id $id or throws an exception if it doesn't exist.
     *
     * @param string $id Order identifier
     *
     * @throws NotFoundHttpException
     *
     * @return OrderInterface
     */
    protected function getOrder($id)
    {
        $order = $this->orderManager->findOneBy(['id' => $id]);

        if (null === $order) {
            throw new NotFoundHttpException(sprintf('Order (%d) not found', $id));
        }

        return $order;
    }
}
