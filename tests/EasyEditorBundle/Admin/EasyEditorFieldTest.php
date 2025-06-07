<?php

declare(strict_types=1);

namespace App\Tests\EasyEditorBundle\Admin;

use Adeliom\EasyEditorBundle\Admin\Field\EasyEditorField;
use PHPUnit\Framework\TestCase;

final class EasyEditorFieldTest extends TestCase
{
    public function testDefaultOptions(): void
    {
        $field = EasyEditorField::new('content');
        $dto = $field->getAsDto();

        self::assertTrue($dto->getCustomOption(EasyEditorField::OPTION_ALLOW_DRAG));
        self::assertTrue($dto->getCustomOption(EasyEditorField::OPTION_ALLOW_ADD));
        self::assertTrue($dto->getCustomOption(EasyEditorField::OPTION_ALLOW_DELETE));
        self::assertFalse($dto->getCustomOption(EasyEditorField::OPTION_SHOW_ENTRY_LABEL));
        self::assertFalse($dto->getCustomOption(EasyEditorField::OPTION_RENDER_EXPANDED));
    }
}
