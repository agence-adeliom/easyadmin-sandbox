<?php

declare(strict_types=1);

namespace App\Tests\EasyFieldsBundle\Form;

use Adeliom\EasyFieldsBundle\Form\ChoiceMaskType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class ChoiceMaskTypeTest extends TestCase
{
    public function testBuildViewSanitize(): void
    {
        $type = new ChoiceMaskType();
        $view = new FormView();
        $form = $this->createMock(FormInterface::class);
        $type->buildView($view, $form, ['map' => ['v' => ['foo.bar', 'bar__foo']]]);
        $this->assertSame(['v' => ['foo__bar', 'bar____foo']], $view->vars['map']);
        $this->assertSame(['foo__bar', 'bar____foo'], $view->vars['all_fields']);
    }
}
