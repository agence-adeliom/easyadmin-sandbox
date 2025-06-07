<?php

declare(strict_types=1);

namespace App\Tests\EasyFieldsBundle\Admin;

use Adeliom\EasyFieldsBundle\Admin\Field\PositionSortableField;
use PHPUnit\Framework\TestCase;

class PositionSortableFieldTest extends TestCase
{
    public function testParentProperty(): void
    {
        $field = PositionSortableField::new('position');
        $this->assertSame('parent', $field->getAsDto()->getCustomOption(PositionSortableField::PARENT_PROPERTY));
        $field->setParentProperty('section');
        $this->assertSame('section', $field->getAsDto()->getCustomOption(PositionSortableField::PARENT_PROPERTY));
    }
}
