<?php
declare(strict_types=1);

namespace App\Tests\EasyBlockBundle\Editor;

use Adeliom\EasyBlockBundle\Editor\SharedBlockType;
use App\Tests\EasyBlockBundle\Fixtures\DummyTranslator;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class SharedBlockTypeTest extends TestCase
{
    public function testBuildBlockAddsEntityField(): void
    {
        $em = $this->createStub(EntityManagerInterface::class);
        $translator = new DummyTranslator();
        $type = new SharedBlockType($em, $translator, 'App\\Entity\\EasyBlock\\Block');

        $builder = $this->createMock(\Symfony\Component\Form\FormBuilderInterface::class);
        $builder->expects($this->once())
            ->method('add')
            ->with('block', \Symfony\Bridge\Doctrine\Form\Type\EntityType::class, $this->arrayHasKey('class'))
            ->willReturnSelf();
        $type->buildBlock($builder, []);

        $this->assertSame('easy.block.editor.shared_block', $type->getName());
        $this->assertSame('<span class="fas fa-shapes"></span>', $type->getIcon());
    }
}
