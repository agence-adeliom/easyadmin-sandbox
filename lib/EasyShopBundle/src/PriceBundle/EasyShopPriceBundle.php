<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\PriceBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @codeCoverageIgnore
 */
class EasyShopPriceBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
    }

    public function boot(): void
    {
        if (0 === $this->getBcScale()) {
            $message = <<<'CONTENT'
[%s]: You need to add a bcscale() method greater than 0 in your AppKernel.php to ensure that prices are correctly computed.
Please refer to documentation: https://sonata-project.org/bundles/ecommerce/develop/doc/reference/bundles/price.html
CONTENT;

            throw new \RuntimeException(sprintf($message, $this->getName()));
        }
    }

    /**
     * Returns bcscale() setted value.
     *
     * @return int
     */
    public function getBcScale()
    {
        $sqrt = bcsqrt('2');

        return \strlen(substr($sqrt, strpos($sqrt, '.') + 1));
    }
}
