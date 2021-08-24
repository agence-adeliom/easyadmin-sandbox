<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\ProductBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends AbstractController
{
    /**
     * @param null $category
     * @param int  $depth
     * @param int  $deep
     *
     * @return Response
     */
    public function listSideMenuCategoriesAction($category = null, $depth = 1, $deep = 0)
    {
        $category = $category ?: $this->get('easy_shop.classification.manager.category')->getRootCategory();

        return $this->render('@SonataProduct/Category/side_menu_category.html.twig', [
          'root_category' => $category,
          'depth' => $depth,
          'deep' => $deep + 1,
        ]);
    }
}
