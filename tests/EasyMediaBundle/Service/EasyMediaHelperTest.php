<?php

declare(strict_types=1);

namespace App\Tests\EasyMediaBundle\Service;

use Adeliom\EasyMediaBundle\Service\EasyMediaHelper;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Routing\RouterInterface;

class EasyMediaHelperTest extends TestCase
{
    public function testMime2ext(): void
    {
        self::assertSame('png', EasyMediaHelper::mime2ext('image/png'));
    }

    public function testFileIsType(): void
    {
        $helper = $this->createHelper(['extended_mimes' => ['image' => ['image/png']]]);
        self::assertTrue($helper->fileIsType('image/png', 'image'));
    }

    private function createHelper(array $extra = []): EasyMediaHelper
    {
        $params = array_merge([
            'folder_entity' => '',
            'media_entity' => '',
            'base_url' => '',
            'sanitized_text' => static fn () => 'random',
            'allowed_fileNames_chars' => '',
            'allowed_folderNames_chars' => '',
            'last_modified_format' => 'Y-m-d',
            'extended_mimes' => [],
        ], $extra);

        $bag = new class($params) implements ContainerBagInterface {
            public function __construct(private array $params) {}
            public function get(string $id){}
            public function has(string $id): bool { return false; }
            public function clear(): void {}
            public function add(array $parameters): void {}
            public function all(): array { return $this->params; }
            public function remove(string $name): void {}
            public function set(string $name, \UnitEnum|array|string|int|float|bool|null $value): void { $this->params[$name] = $value; }
            public function resolve(): void {}
            public function resolveValue(mixed $value): mixed { return $value; }
            public function escapeValue(mixed $value): mixed { return $value; }
            public function unescapeValue(mixed $value): mixed { return $value; }
        };

        return new EasyMediaHelper($bag, $this->createMock(EntityManagerInterface::class), $this->createMock(RouterInterface::class));
    }
}
