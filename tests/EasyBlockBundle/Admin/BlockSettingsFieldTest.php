<?php
declare(strict_types=1);

namespace App\Tests\EasyBlockBundle\Admin;

use Adeliom\EasyBlockBundle\Admin\Field\BlockSettingsField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use PHPUnit\Framework\TestCase;

class BlockSettingsFieldTest extends TestCase
{
    public function testNewCreatesFieldWithDefaults(): void
    {
        $field = BlockSettingsField::new('settings', 'Settings');
        $dto = $field->getAsDto();

        $this->assertSame('settings', $dto->getProperty());
        $this->assertSame('Settings', $dto->getLabel());
        $this->assertFalse($dto->isDisplayedOn(Crud::PAGE_INDEX));
        $this->assertSame('', $dto->getDefaultColumns());
    }
}
