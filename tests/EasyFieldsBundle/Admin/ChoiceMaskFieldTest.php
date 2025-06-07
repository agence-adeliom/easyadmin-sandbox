<?php

declare(strict_types=1);

namespace App\Tests\EasyFieldsBundle\Admin;

use Adeliom\EasyFieldsBundle\Admin\Field\ChoiceMaskField;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ChoiceMaskFieldTest extends TestCase
{
    public function testSetChoicesWithInvalidType(): void
    {
        $field = ChoiceMaskField::new('choice');
        $this->expectException(InvalidArgumentException::class);
        $field->setChoices('invalid');
    }

    public function testRenderAsBadgesWithInvalidBadgeType(): void
    {
        $field = ChoiceMaskField::new('choice');
        $this->expectException(InvalidArgumentException::class);
        $field->renderAsBadges(['foo' => 'invalid']);
    }
}
