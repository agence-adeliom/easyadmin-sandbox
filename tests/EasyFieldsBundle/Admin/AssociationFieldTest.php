<?php

declare(strict_types=1);

namespace App\Tests\EasyFieldsBundle\Admin;

use Adeliom\EasyFieldsBundle\Admin\Field\AssociationField;
use PHPUnit\Framework\TestCase;

class AssociationFieldTest extends TestCase
{
    public function testAllowAdd(): void
    {
        $field = AssociationField::new('prop');
        $field->allowAdd();
        $this->assertTrue($field->getAsDto()->getCustomOption(AssociationField::OPTION_ALLOW_ADD));
    }

    public function testListDisplayColumns(): void
    {
        $field = AssociationField::new('prop');
        $field->listDisplayColumns(2);
        $option = $field->getAsDto()->getCustomOption(AssociationField::OPTION_LIST_DISPLAY_COLUMNS);
        $this->assertSame(['columns' => [2], 'separator' => '-'], $option);
    }
}
