<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\ProductBundle\Controller;

use Adeliom\EasyShop\Component\Basket\BasketElementInterface;
use Adeliom\EasyShop\Component\Basket\BasketInterface;
use Adeliom\EasyShop\Component\Product\Pool;
use Adeliom\EasyShop\Component\Product\ProductInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class BaseProductController extends AbstractController
{
    /**
     * @param $product
     *
     * @throws NotFoundHttpException
     *
     * @return Response
     */
    public function viewAction($product)
    {
        if (!\is_object($product)) {
            throw new NotFoundHttpException('invalid product instance');
        }

        $provider = $this->get('easy_shop.product.pool')->getProvider($product);

        $formBuilder = $this->get('form.factory')->createNamedBuilder('add_basket', FormType::class, null, ['data_class' => $this->container->getParameter('easy_shop.basket.basket_element.class'), 'csrf_protection' => false]);
        $provider->defineAddBasketForm($product, $formBuilder);

        $form = $formBuilder->getForm()->createView();

        $currency = $this->get('easy_shop.price.currency.detector')->getCurrency();

        // Add twitter/FB metadata
        $this->updateSeoMeta($product, $currency);

        return $this->render(
            sprintf('%s/view.html.twig', $provider->getTemplatesPath()),
            [
                'provider' => $provider,
                'product' => $product,
                'cheapest_variation' => $provider->getCheapestEnabledVariation($product),
                'currency' => $currency,
                'form' => $form,
            ]
        );
    }

    /**
     * Renders product properties.
     *
     * @return Response
     */
    public function renderPropertiesAction(ProductInterface $product)
    {
        $provider = $this->get('easy_shop.product.pool')->getProvider($product);

        return $this->render(sprintf('%s/properties.html.twig', $provider->getTemplatesPath()), [
            'product' => $product,
        ]);
    }

    /**
     * @return Response
     */
    public function renderFormBasketElementAction(FormView $formView, BasketElementInterface $basketElement, BasketInterface $basket)
    {
        /** @var Pool $pool */
        $pool = $this->get('easy_shop.product.pool');
        $provider = $pool->getProvider($basketElement->getProduct());

        return $this->render(sprintf('%s/form_basket_element.html.twig', $provider->getTemplatesPath()), [
            'formView' => $formView,
            'basketElement' => $basketElement,
            'basket' => $basket,
        ]);
    }

    /**
     * @return Response
     */
    public function renderFinalReviewBasketElementAction(BasketElementInterface $basketElement, BasketInterface $basket)
    {
        $provider = $this->get('easy_shop.product.pool')->getProvider($basketElement->getProduct());

        return $this->render(sprintf('%s/final_review_basket_element.html.twig', $provider->getTemplatesPath()), [
            'basketElement' => $basketElement,
            'basket' => $basket,
        ]);
    }

    /**
     * @param $product
     *
     * @throws NotFoundHttpException
     *
     * @return Response
     */
    public function viewVariationsAction($product)
    {
        if (!\is_object($product)) {
            throw new NotFoundHttpException('invalid product instance');
        }

        $provider = $this->get('easy_shop.product.pool')->getProvider($product);

        return $this->render(sprintf('%s/view_variations.html.twig', $provider->getTemplatesPath()), [
            'product' => $product,
        ]);
    }

    /**
     * @throws NotFoundHttpException
     *
     * @return JsonResponse|RedirectResponse
     */
    public function variationToProductAction(Request $request, ProductInterface $product, ?ProductInterface $variation = null)
    {
        $provider = $this->get('easy_shop.product.pool')->getProvider($product);

        if (!$provider->hasEnabledVariations($product)) {
            throw new NotFoundHttpException('invalid product instance (no variations)');
        }

        if (null === $variation || 0 === $provider->getStockAvailable($variation)) {
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(['error' => $this->get('translator')->trans('variation_not_found', [], 'SonataProductBundle')]);
            }

            $message = $this->get('translator')->trans('variation_not_found', [], 'SonataProductBundle');
            $this->get('session')->getFlashBag()->add('easy_shop_product_error', $message);

            // Go to master product
            $variation = $product;
        }

        $url = $this->generateUrl('easy_shop_product_view', [
            'productId' => $variation->getId(),
            'slug' => $variation->getSlug(),
        ]);

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(['variation_url' => $url]);
        }

        return $this->redirect($url);
    }

    /**
     * @param string|null $currency
     */
    protected function updateSeoMeta(ProductInterface $product, $currency = null): void
    {
        $seoPage = $this->get('easy_shop.seo.page');

        $seoPage->setTitle($product->getName());
        $this->get('easy_shop.product.seo.facebook')->alterPage($seoPage, $product);
        $this->get('easy_shop.product.seo.twitter')->alterPage($seoPage, $product);
    }
}
