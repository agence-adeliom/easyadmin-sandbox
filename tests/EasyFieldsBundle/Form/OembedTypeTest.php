<?php

declare(strict_types=1);

namespace App\Tests\EasyFieldsBundle\Form;

use Adeliom\EasyFieldsBundle\Form\OembedType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

class OembedTypeTest extends TestCase
{
    public function testParent(): void
    {
        $this->assertSame(UrlType::class, (new OembedType())->getParent());
    }
}
