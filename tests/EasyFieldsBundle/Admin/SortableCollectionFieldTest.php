<?php

declare(strict_types=1);

namespace App\Tests\EasyFieldsBundle\Admin;

use Adeliom\EasyFieldsBundle\Admin\Field\SortableCollectionField;
use PHPUnit\Framework\TestCase;

class SortableCollectionFieldTest extends TestCase
{
    public function testAllowAdd(): void
    {
        $field = SortableCollectionField::new('collection');
        $this->assertTrue($field->getAsDto()->getCustomOption(SortableCollectionField::OPTION_ALLOW_ADD));
        $field->allowAdd(false);
        $this->assertFalse($field->getAsDto()->getCustomOption(SortableCollectionField::OPTION_ALLOW_ADD));
    }
}
