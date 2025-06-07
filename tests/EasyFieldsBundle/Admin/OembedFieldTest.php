<?php

declare(strict_types=1);

namespace App\Tests\EasyFieldsBundle\Admin;

use Adeliom\EasyFieldsBundle\Admin\Field\OembedField;
use PHPUnit\Framework\TestCase;

class OembedFieldTest extends TestCase
{
    public function testTemplatePath(): void
    {
        $field = OembedField::new('oembed');
        $this->assertSame('@EasyFields/crud/field/oembed.html.twig', $field->getAsDto()->getTemplatePath());
    }
}
