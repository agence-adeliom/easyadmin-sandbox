<?php

namespace Adeliom\EasyShopBundle\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;

trait EasyShopDashboardTrait {


    public function getSyliusEntity(string $model){
        $parameterBag = $this->container->get("parameter_bag");
        return $parameterBag->get(sprintf("sylius.model.%s.class", $model));
    }

    public static function SYLIUS_SERVICES(): array {
        return [
            "knp_menu.menu_provider.chain" => '?Knp\Menu\Provider\MenuProviderInterface'
        ];
    }


    public function syliusItems(): iterable
    {
        $menu = $this->container->get("knp_menu.menu_provider.chain")->get("sylius.admin.main");
        $routes = $this->container->get("router")->getRouteCollection();

        foreach ($menu->getChildren() as $child){
            yield MenuItem::section($child->getLabel());
            foreach ($child->getChildren() as $subChild){
                $route = $subChild->getExtra("routes")[0]["route"];
                $parameters = $subChild->getExtra("routes")[0]["parameters"];
                $routeItem = $routes->get($route);
                $default = $routeItem->getDefaults();
//                self::traverse_arr($default);
                yield MenuItem::linkToRoute($subChild->getLabel(),
                    'fas fa-'.$subChild->getLabelAttribute('icon'),
                    $route,
                    array_merge($parameters, $default));
            }
        }
    }

    private static function traverse_arr( &$array ){
        foreach( $array as $key => $value ){
            if( is_array( $value ) ){
                self::traverse_arr( $array[ $key ] );
            }
            else if( strpos($value, "@SyliusAdmin/") === 0 ){
                $array[ $key ] = str_replace("@SyliusAdmin/", "@EasyShop/SyliusAdmin/", $value);
            }else if( strpos($value, "@SyliusAdmin\\") === 0 ){
                $array[ $key ] = str_replace("@SyliusAdmin\\","@EasyShop\\SyliusAdmin\\", $value);
            }
        }
    }

}
