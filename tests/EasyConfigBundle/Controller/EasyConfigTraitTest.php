<?php
declare(strict_types=1);

namespace App\Tests\EasyConfigBundle\Controller;

use Adeliom\EasyConfigBundle\Controller\Admin\EasyConfigTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class EasyConfigTraitTest extends TestCase
{
    public function testConfigMenuEntryReturnsCrudItem(): void
    {
        $container = new Container();
        $container->set('parameter_bag', new ParameterBag(['easy_config.config_class' => 'App\\Entity\\EasyConfig\\Config']));

        $object = new class($container) {
            use EasyConfigTrait;
            public function __construct(public Container $container)
            {
            }
        };

        $items = iterator_to_array($object->configMenuEntry());
        self::assertCount(1, $items);
        $item = $items[0];
        $params = $item->getAsDto()->getRouteParameters();
        self::assertSame('App\\Entity\\EasyConfig\\Config', $params['entityFqcn']);
    }
}
