<?php

declare(strict_types=1);

namespace Adeliom\EasyShop\BasketBundle;

use Adeliom\EasyShop\BasketBundle\DependencyInjection\Compiler\GlobalVariableCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EasyShopBasketBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new GlobalVariableCompilerPass());
    }
}
