<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\CustomerBundle;

use Adeliom\EasyShop\CoreBundle\Form\FormHelper;
use Adeliom\EasyShop\CustomerBundle\DependencyInjection\Compiler\GlobalVariablesCompilerPass;
use Adeliom\EasyShop\CustomerBundle\Form\Type\AddressType;
use Adeliom\EasyShop\CustomerBundle\Form\Type\AddressTypeType;
use Adeliom\EasyShop\CustomerBundle\Form\Type\ApiAddressType;
use Adeliom\EasyShop\CustomerBundle\Form\Type\ApiCustomerType;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EasyShopCustomerBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new GlobalVariablesCompilerPass());
    }
}
