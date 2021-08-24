<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\ProductBundle;

use Adeliom\EasyShop\Component\Currency\CurrencyFormType;
use Adeliom\EasyShop\Component\Form\Type\VariationChoiceType;
use Adeliom\EasyShop\CoreBundle\Form\FormHelper;
use Adeliom\EasyShop\ProductBundle\DependencyInjection\Compiler\AddProductProviderCompilerPass;
use Adeliom\EasyShop\ProductBundle\DependencyInjection\Compiler\TwigStringExtensionCompilerPass;
use Adeliom\EasyShop\ProductBundle\Form\Type\ApiProductParentType;
use Adeliom\EasyShop\ProductBundle\Form\Type\ApiProductType;
use Adeliom\EasyShop\ProductBundle\Form\Type\ProductDeliveryStatusType;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EasyShopProductBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new AddProductProviderCompilerPass());
        $container->addCompilerPass(new TwigStringExtensionCompilerPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 1);
    }
}
