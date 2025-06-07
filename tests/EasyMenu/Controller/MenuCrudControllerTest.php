<?php
declare(strict_types=1);

namespace App\Tests\EasyMenu\Controller;

use Adeliom\EasyMenuBundle\Controller\MenuCrudController;
use App\Entity\EasyMenu\Menu;
use PHPUnit\Framework\TestCase;

final class MenuCrudControllerTest extends TestCase
{
    public function testInformationsFieldsProvideNameAndCode(): void
    {
        $controller = new class() extends MenuCrudController {
            public static function getEntityFqcn(): string
            {
                return Menu::class;
            }
        };

        $fields = iterator_to_array($controller->informationsFields('new', null));

        self::assertCount(3, $fields);
        self::assertSame('name', $fields[1]->getAsDto()->getProperty());
        self::assertSame('code', $fields[2]->getAsDto()->getProperty());
    }
}
