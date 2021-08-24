<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\ProductBundle\Controller;

use Adeliom\EasyShop\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProductAdminController extends Controller
{
    /**
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     *
     * @return \Symfony\Bundle\FrameworkBundle\Controller\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(?Request $request = null)
    {
        if (false === $this->admin->isGranted('CREATE')) {
            throw new AccessDeniedException();
        }

        $parameters = $this->admin->getPersistentParameters();

        if (!$parameters['provider']) {
            return $this->render('@SonataProduct/ProductAdmin/select_provider.html.twig', [
                'providers' => $this->get('easy_shop.product.pool')->getProducts(),
                'base_template' => $this->getBaseTemplate(),
                'admin' => $this->admin,
                'action' => 'create',
            ]);
        }

        return parent::createAction($request);
    }

    /**
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showVariationsAction(?Request $request = null)
    {
        $id = $request->get($this->admin->getIdParameter());

        if (!$product = $this->admin->getObject($id)) {
            throw new NotFoundHttpException('Product not found.');
        }

        return $this->render('@SonataProduct/ProductAdmin/variations.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * Return the Product Pool.
     *
     * @return \Adeliom\EasyShop\Component\Product\Pool
     */
    protected function getProductPool()
    {
        return $this->get('easy_shop.product.pool');
    }

    /**
     * Return the Product Pool.
     *
     * @return \Symfony\Bundle\FrameworkBundle\Translation\Translator
     */
    protected function getTranslator()
    {
        return $this->get('translator');
    }

    /**
     * Return the Product manager.
     *
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getProductManager()
    {
        return $this->get('doctrine')->getManagerForClass($this->admin->getClass());
    }
}
