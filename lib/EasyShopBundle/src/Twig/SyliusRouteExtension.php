<?php

namespace Adeliom\EasyShopBundle\Twig;

use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Sylius\Component\Core\Model\AdjustmentInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Order\Model\AdjustmentInterface as BaseAdjustmentInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SyliusRouteExtension extends AbstractExtension
{
    protected $adminUrlGenerator;
    protected $router;
    public function __construct(AdminUrlGenerator $adminUrlGenerator, RouterInterface $router)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
        $this->router = $router;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('sylius_route', [$this, 'getRoute']),
        ];
    }

    public function getRoute(string $name, array $parameters = [], bool $relative = false): string
    {
        $routes = $this->router->getRouteCollection();
        if ($routeItem = $routes->get($name)) {
            $default = $routeItem->getDefaults();

            return $this->adminUrlGenerator->setRoute($name, array_merge($parameters, $default))->generateUrl();
        }
        return "";
    }
}
