<?php
declare(strict_types=1);

namespace App\Tests\EasyConfigBundle\Controller;

use Adeliom\EasyConfigBundle\Controller\Admin\EasyConfigCrudController;
use Adeliom\EasyConfigBundle\Entity\Config;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use PHPUnit\Framework\TestCase;

class EasyConfigCrudControllerTest extends TestCase
{
    private function getController(): TestController
    {
        return new TestController();
    }

    public function testAvailableTypes(): void
    {
        $controller = $this->getController();
        $types = $controller->publicGetAvailableTypes();

        self::assertArrayHasKey('easy_config.types.text', $types);
        self::assertContains('text', $types);
    }

    public function testFieldMap(): void
    {
        $controller = $this->getController();
        $map = $controller->publicGetFieldMap();

        self::assertArrayHasKey('text', $map);
        self::assertSame(['text'], $map['text']);
    }

    public function testIsEditable(): void
    {
        $config = (new Config())->setType('text');
        $controller = $this->getController();

        self::assertTrue($controller->publicIsEditable('text', $config, Crud::PAGE_NEW));

        $ref = new \ReflectionProperty(Config::class, 'id');
        $ref->setAccessible(true);
        $ref->setValue($config, 1);

        self::assertFalse($controller->publicIsEditable('json', $config, Crud::PAGE_DETAIL));
        self::assertTrue($controller->publicIsEditable('text', $config, Crud::PAGE_DETAIL));
        self::assertTrue($controller->publicIsEditable('text', $config, Crud::PAGE_EDIT));
    }
}

class TestController extends EasyConfigCrudController
{
    public static function getEntityFqcn(): string
    {
        return Config::class;
    }

    public function publicGetAvailableTypes(): array
    {
        return $this->getAvailableTypes();
    }

    public function publicGetFieldMap(): array
    {
        return $this->getFieldMap();
    }

    public function publicIsEditable(string $type, object $config, string $pageName): bool
    {
        return self::isEditable($type, $config, $pageName);
    }
}
