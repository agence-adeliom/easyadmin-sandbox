<?php

namespace App\Controller\Page;

use Adeliom\EasyPageBundle\Controller\BasePageController;

use App\Entity\Page;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PageController extends BasePageController
{
    public function custom(Request $request, Page $page): Response
    {
        return $this->json($page, Response::HTTP_OK, [], ['groups' => 'main']);
    }
}
