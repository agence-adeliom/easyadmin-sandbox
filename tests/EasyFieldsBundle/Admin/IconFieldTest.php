<?php

declare(strict_types=1);

namespace App\Tests\EasyFieldsBundle\Admin;

use Adeliom\EasyFieldsBundle\Admin\Field\IconField;
use PHPUnit\Framework\TestCase;

class IconFieldTest extends TestCase
{
    public function testSetRequired(): void
    {
        $field = IconField::new('icon');
        $field->setRequired(false);
        $this->assertFalse($field->getAsDto()->getFormTypeOption('required'));
    }
}
