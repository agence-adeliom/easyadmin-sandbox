<?php

declare(strict_types=1);

namespace App\Tests\EasyFieldsBundle\Admin;

use Adeliom\EasyFieldsBundle\Admin\Field\FormTypeField;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use PHPUnit\Framework\TestCase;

class FormTypeFieldTest extends TestCase
{
    public function testFormType(): void
    {
        $field = FormTypeField::new('email', 'Email', EmailType::class);
        $this->assertSame(EmailType::class, $field->getAsDto()->getFormType());
    }
}
