<?php

declare(strict_types=1);

namespace App\Tests\EasyMediaBundle\Service;

use Adeliom\EasyMediaBundle\Service\EasyMediaHelper;
use Adeliom\EasyMediaBundle\Service\EasyMediaManager;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemOperator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class EasyMediaManagerTest extends TestCase
{
    public function testGetHelperReturnsHelper(): void
    {
        $manager = $this->createManager();
        self::assertInstanceOf(EasyMediaHelper::class, $manager->getHelper());
    }

    private function createManager(): EasyMediaManager
    {
        $filesystem = $this->createMock(FilesystemOperator::class);
        $bag = new class() implements ContainerBagInterface {
            public function get(string $id){}
            public function has(string $id): bool { return false; }
            public function clear(): void {}
            public function add(array $parameters): void {}
            public function all(): array { return []; }
            public function remove(string $name): void {}
            public function set(string $name, \UnitEnum|array|string|int|float|bool|null $value): void {}
            public function resolve(): void {}
            public function resolveValue(mixed $value): mixed { return $value; }
            public function escapeValue(mixed $value): mixed { return $value; }
            public function unescapeValue(mixed $value): mixed { return $value; }
        };
        $helper = new EasyMediaHelper($bag, $this->createMock(EntityManagerInterface::class), $this->createMock(RouterInterface::class));
        $em = $this->createMock(EntityManagerInterface::class);
        $translator = $this->createMock(TranslatorInterface::class);
        $dispatcher = $this->createMock(EventDispatcherInterface::class);

        return new EasyMediaManager($filesystem, $helper, $em, $bag, $translator, $dispatcher);
    }
}
