<?php

namespace App\Tests\TestKernels;

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class TestTrailingSlashFalseKernel extends Kernel
{
    use MicroKernelTrait;

    protected function configureContainer(ContainerConfigurator $container): void
    {
        parent::configureContainer($container);
        $container->import(__DIR__.'/../config/easy_page_trailing_slash_false.yaml');
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        parent::configureRoutes($routes);
    }
}
