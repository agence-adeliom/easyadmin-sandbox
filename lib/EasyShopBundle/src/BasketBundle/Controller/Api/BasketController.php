<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\BasketBundle\Controller\Api;

use Adeliom\EasyShop\BasketBundle\Form\ApiBasketElementType;
use Adeliom\EasyShop\BasketBundle\Form\ApiBasketType;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Adeliom\EasyShop\Component\Basket\BasketBuilderInterface;
use Adeliom\EasyShop\Component\Basket\BasketElementInterface;
use Adeliom\EasyShop\Component\Basket\BasketElementManagerInterface;
use Adeliom\EasyShop\Component\Basket\BasketInterface;
use Adeliom\EasyShop\Component\Basket\BasketManagerInterface;
use Adeliom\EasyShop\Component\Product\ProductInterface;
use Adeliom\EasyShop\Component\Product\ProductManagerInterface;
use Adeliom\EasyShop\DatagridBundle\Pager\PagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class BasketController
{
    /**
     * @var BasketManagerInterface
     */
    protected $basketManager;

    /**
     * @var BasketElementManagerInterface
     */
    protected $basketElementManager;

    /**
     * @var ProductManagerInterface
     */
    protected $productManager;

    /**
     * @var BasketBuilderInterface
     */
    protected $basketBuilder;

    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @param BasketManagerInterface        $basketManager        EasyShop ecommerce basket manager
     * @param BasketElementManagerInterface $basketElementManager EasyShop ecommerce basket element manager
     * @param ProductManagerInterface       $productManager       EasyShop ecommerce product manager
     * @param BasketBuilderInterface        $basketBuilder        EasyShop ecommerce basket builder
     * @param FormFactoryInterface          $formFactory          Symfony form factory
     */
    public function __construct(BasketManagerInterface $basketManager, BasketElementManagerInterface $basketElementManager, ProductManagerInterface $productManager, BasketBuilderInterface $basketBuilder, FormFactoryInterface $formFactory)
    {
        $this->basketManager = $basketManager;
        $this->basketElementManager = $basketElementManager;
        $this->productManager = $productManager;
        $this->basketBuilder = $basketBuilder;
        $this->formFactory = $formFactory;
    }

    /**
     * Returns a paginated list of baskets.
     *
     * @ApiDoc(
     *  resource=true,
     *  output={"class"="Adeliom\EasyShop\DatagridBundle\Pager\PagerInterface", "groups"={"easy_shop_api_read"}}
     * )
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="Page for baskets list pagination (1-indexed)")
     * @Rest\QueryParam(name="count", requirements="\d+", default="10", description="Number of baskets by page")
     * @Rest\QueryParam(name="orderBy", map=true, requirements="ASC|DESC", nullable=true, strict=true, description="Sort specification for the resultset (key is field, value is direction")
     *
     * @Rest\View(serializerGroups={"easy_shop_api_read"}, serializerEnableMaxDepthChecks=true)
     *
     * @return PagerInterface
     */
    public function getBasketsAction(ParamFetcherInterface $paramFetcher)
    {
        // No filters implemented as of right now
        $supportedCriteria = [
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

        return $this->basketManager->getPager($criteria, $page, $limit, $sort);
    }

    /**
     * Retrieves a specific basket.
     *
     * @ApiDoc(
     *  requirements={
     *      {"name"="id", "dataType"="string", "description"="Basket identifier"}
     *  },
     *  output={"class"="Adeliom\EasyShop\Component\Basket\BasketInterface", "groups"={"easy_shop_api_read"}},
     *  statusCodes={
     *      200="Returned when successful",
     *      404="Returned when basket is not found"
     *  }
     * )
     *
     * @Rest\View(serializerGroups={"easy_shop_api_read"}, serializerEnableMaxDepthChecks=true)
     *
     * @param string $id Basket identifier
     *
     * @return BasketInterface
     */
    public function getBasketAction($id)
    {
        return $this->getBasket($id);
    }

    /**
     * Retrieves a specific basket's elements.
     *
     * @ApiDoc(
     *  requirements={
     *      {"name"="id", "dataType"="string", "description"="Basket identifier"}
     *  },
     *  output={"class"="Adeliom\EasyShop\Component\Basket\BasketInterface", "groups"={"easy_shop_api_read"}},
     *  statusCodes={
     *      200="Returned when successful",
     *      404="Returned when basket is not found"
     *  }
     * )
     *
     * @Rest\View(serializerGroups={"easy_shop_api_read"}, serializerEnableMaxDepthChecks=true)
     *
     * @param string $id Basket identifier
     *
     * @return BasketElementInterface[]
     */
    public function getBasketBasketelementsAction($id)
    {
        return $this->getBasket($id)->getBasketElements();
    }

    /**
     * Adds a basket.
     *
     * @ApiDoc(
     *  input={"class"="easy_shop_basket_api_form_basket", "name"="", "groups"={"easy_shop_api_write"}},
     *  output={"class"="Adeliom\EasyShop\Component\Basket\BasketInterface", "groups"={"easy_shop_api_read"}},
     *  statusCodes={
     *      200="Returned when successful",
     *      400="Returned when an error has occurred while basket creation",
     *      404="Returned when unable to find basket"
     *  }
     * )
     *
     * @param Request $request Symfony request
     *
     * @return View|FormInterface
     */
    public function postBasketAction(Request $request)
    {
        return $this->handleWriteBasket($request);
    }

    /**
     * Updates a basket.
     *
     * @ApiDoc(
     *  requirements={
     *      {"name"="id", "dataType"="string", "description"="Basket identifier"}
     *  },
     *  input={"class"="easy_shop_basket_api_form_basket", "name"="", "groups"={"easy_shop_api_write"}},
     *  output={"class"="Adeliom\EasyShop\Component\Basket\BasketInterface", "groups"={"easy_shop_api_read"}},
     *  statusCodes={
     *      200="Returned when successful",
     *      400="Returned when an error has occurred while basket update",
     *      404="Returned when unable to find basket"
     *  }
     * )
     *
     * @param string  $id      Basket identifier
     * @param Request $request Symfony request
     *
     * @return View|FormInterface
     */
    public function putBasketAction($id, Request $request)
    {
        return $this->handleWriteBasket($request, $id);
    }

    /**
     * Deletes a basket.
     *
     * @ApiDoc(
     *  requirements={
     *      {"name"="id", "dataType"="string", "description"="Basket identifier"}
     *  },
     *  statusCodes={
     *      200="Returned when basket is successfully deleted",
     *      400="Returned when an error has occurred while basket deletion",
     *      404="Returned when unable to find basket"
     *  }
     * )
     *
     * @param string $id Basket identifier
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteBasketAction($id)
    {
        $basket = $this->getBasket($id);

        try {
            $this->basketManager->delete($basket);
        } catch (\Exception $e) {
            return View::create(['error' => $e->getMessage()], 400);
        }

        return ['deleted' => true];
    }

    /**
     * Adds a basket element to a basket.
     *
     * @ApiDoc(
     *  requirements={
     *      {"name"="id", "dataType"="string", "description"="Basket identifier"},
     *  },
     *  input={"class"="easy_shop_basket_api_form_basket_element", "name"="", "groups"={"easy_shop_api_write"}},
     *  output={"class"="Adeliom\EasyShop\Component\Basket\BasketInterface", "groups"={"easy_shop_api_read"}},
     *  statusCodes={
     *      200="Returned when successful",
     *      400="Returned when an error has occurred while basket/element attachment",
     *      404="Returned when basket or product not found",
     *  }
     * )
     *
     * @param string  $id      Basket identifier
     * @param Request $request Symfony request
     *
     * @return View|FormInterface
     */
    public function postBasketBasketelementsAction($id, Request $request)
    {
        return $this->handleWriteBasketElement($id, $request);
    }

    /**
     * Updates a basket element of a basket.
     *
     * @ApiDoc(
     *  requirements={
     *      {"name"="basketId", "dataType"="string", "description"="Basket identifier"},
     *      {"name"="elementId", "dataType"="string", "description"="Element identifier"},
     *  },
     *  input={"class"="easy_shop_basket_api_form_basket_element", "name"="", "groups"={"easy_shop_api_write"}},
     *  output={"class"="Adeliom\EasyShop\Component\Basket\BasketInterface", "groups"={"easy_shop_api_read"}},
     *  statusCodes={
     *      200="Returned when successful",
     *      400="Returned when an error has occurred while basket/element attachment",
     *      404="Returned when basket or basket element not found",
     *  }
     * )
     *
     * @param string  $basketId  Basket identifier
     * @param string  $elementId Basket element identifier
     * @param Request $request   Symfony request
     *
     * @return View|FormInterface
     */
    public function putBasketBasketelementsAction($basketId, $elementId, Request $request)
    {
        return $this->handleWriteBasketElement($basketId, $request, $elementId);
    }

    /**
     * Deletes a basket element from a basket.
     *
     * @ApiDoc(
     *  requirements={
     *      {"name"="basketId", "dataType"="string", "description"="Basket identifier"},
     *      {"name"="elementId", "dataType"="string", "description"="Element identifier"},
     *  },
     *  statusCodes={
     *      200="Returned when basket is successfully deleted",
     *      400="Returned when an error has occurred while basket element deletion",
     *      404="Returned when unable to find basket or basket element"
     *  }
     * )
     *
     * @param string $basketId  Basket identifier
     * @param string $elementId Basket element identifier
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteBasketBasketelementsAction($basketId, $elementId)
    {
        $basket = $this->getBasket($basketId);
        $this->basketBuilder->build($basket);

        $elements = $basket->getBasketElements();

        foreach ($elements as $key => $basketElement) {
            if ($basketElement->getId() === $elementId) {
                unset($elements[$key]);
                $this->basketBuilder->build($basket);
            }
        }

        try {
            $basket->setBasketElements($elements);
            $this->basketManager->save($basket);
        } catch (\Exception $e) {
            return View::create(['error' => $e->getMessage()], 400);
        }

        $context = new Context();
        $context->setGroups(['easy_shop_api_read']);

        // simplify when dropping FOSRest < 2.1
        if (method_exists($context, 'enableMaxDepth')) {
            $context->enableMaxDepth();
        } else {
            $context->setMaxDepth(10);
        }

        $view = View::create($basket);
        $view->setContext($context);

        return $view;
    }

    /**
     * Write a basket, this method is used by both POST and PUT action methods.
     *
     * @param Request     $request Symfony request
     * @param string|null $id      Basket identifier
     *
     * @return View|FormInterface
     */
    protected function handleWriteBasket($request, $id = null)
    {
        $basket = $id ? $this->getBasket($id) : null;

        $form = $this->formFactory->createNamed(null, ApiBasketType::class, $basket, [
            'csrf_protection' => false,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $basket = $form->getData();
            if ($basket->getCustomerId()) {
                $this->checkExistingCustomerBasket($basket->getCustomerId());
            }
            $this->basketManager->save($basket);

            $context = new Context();
            $context->setGroups(['easy_shop_api_read']);

            // simplify when dropping FOSRest < 2.1
            if (method_exists($context, 'enableMaxDepth')) {
                $context->enableMaxDepth();
            } else {
                $context->setMaxDepth(10);
            }

            $view = View::create($basket);
            $view->setContext($context);

            return $view;
        }

        return $form;
    }

    /**
     * Write a basket element, this method is used by both POST and PUT action methods.
     *
     * @param string  $basketId  EasyShop ecommerce basket identifier
     * @param Request $request   Symfony Request service
     * @param string  $elementId EasyShop ecommerce basket element identifier
     *
     * @return View|FormInterface
     */
    protected function handleWriteBasketElement($basketId, $request, $elementId = null)
    {
        $basket = $this->getBasket($basketId);
        $this->basketBuilder->build($basket);

        $productId = $request->request->get('productId');
        $product = $this->getProduct($productId);

        $productPool = $basket->getProductPool();

        $code = $productPool->getProductCode($product);
        $productDefinition = $productPool->getProduct($code);

        $basketElement = $elementId ? $this->getBasketElement($elementId) : $this->basketElementManager->create();
        $basketElement->setProductDefinition($productDefinition);

        $form = $this->formFactory->createNamed(null, ApiBasketElementType::class, $basketElement, [
            'csrf_protection' => false,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $basketElement = $form->getData();

            if ($elementId) {
                $this->basketElementManager->save($basketElement);
            } else {
                $basket->addBasketElement($basketElement);
                $this->basketManager->save($basket);
            }

            $context = new Context();
            $context->setGroups(['easy_shop_api_read']);

            // simplify when dropping FOSRest < 2.1
            if (method_exists($context, 'enableMaxDepth')) {
                $context->enableMaxDepth();
            } else {
                $context->setMaxDepth(10);
            }

            $view = View::create($basket);
            $view->setContext($context);

            return $view;
        }

        return $form;
    }

    /**
     * Retrieves basket with identifier $id or throws an exception if it doesn't exist.
     *
     * @param string $id Basket identifier
     *
     * @throws NotFoundHttpException
     *
     * @return BasketInterface
     */
    protected function getBasket($id)
    {
        $basket = $this->basketManager->findOneBy(['id' => $id]);

        if (null === $basket) {
            throw new NotFoundHttpException(sprintf('Basket (%d) not found', $id));
        }

        return $basket;
    }

    /**
     * Retrieves basket element with identifier $id or throws an exception if it doesn't exist.
     *
     * @param string $id BasketElement identifier
     *
     * @throws NotFoundHttpException
     *
     * @return BasketElementInterface
     */
    protected function getBasketElement($id)
    {
        $basketElement = $this->basketElementManager->findOneBy(['id' => $id]);

        if (null === $basketElement) {
            throw new NotFoundHttpException(sprintf('Basket element (%d) not found', $id));
        }

        return $basketElement;
    }

    /**
     * Retrieves product with identifier $id or throws an exception if it doesn't exist.
     *
     * @param string $id Product identifier
     *
     * @throws NotFoundHttpException
     *
     * @return ProductInterface
     */
    protected function getProduct($id)
    {
        $product = $this->productManager->findOneBy(['id' => $id]);

        if (null === $product) {
            throw new NotFoundHttpException(sprintf('Product (%d) not found', $id));
        }

        return $product;
    }

    /**
     * Throws an exception if it already exists a basket with a specific customer identifier.
     *
     * @param string $customerId Customer identifier
     *
     * @throws HttpException
     */
    protected function checkExistingCustomerBasket($customerId): void
    {
        $basket = $this->basketManager->findOneBy(['customer' => $customerId]);
        if ($basket instanceof BasketInterface) {
            throw new HttpException(400, sprintf('Customer (%d) already has a basket', $customerId));
        }
    }
}
