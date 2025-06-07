<?php

declare(strict_types=1);

namespace App\Tests\EasyFieldsBundle\Admin;

use Adeliom\EasyFieldsBundle\Admin\Field\TranslationField;
use PHPUnit\Framework\TestCase;

class TranslationFieldTest extends TestCase
{
    public function testHideOnIndex(): void
    {
        $field = TranslationField::new('translation');
        $this->assertTrue($field->getAsDto()->getDisplayedOn()->has('edit'));
        $this->assertTrue($field->getAsDto()->getDisplayedOn()->has('new'));
    }
}
