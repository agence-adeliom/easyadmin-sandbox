<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\CustomerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

final class DashboardController extends AbstractController
{
    public function dashboardAction(): Response
    {
        return $this->render('@SonataCustomer/Profile/dashboard.html.twig', [
            'blocks' => $this->container->getParameter('easy_shop.customer.profile.blocks'),
        ]);
    }
}
