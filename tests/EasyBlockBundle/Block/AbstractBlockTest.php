<?php
declare(strict_types=1);

namespace App\Tests\EasyBlockBundle\Block;

use App\Tests\EasyBlockBundle\Fixtures\DummyBlockType;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Forms;

class AbstractBlockTest extends TestCase
{
    public function testBuildFormAddsHiddenFields(): void
    {
        $manager = $this->createStub(EntityManagerInterface::class);
        $blockType = new DummyBlockType($manager);
        $factory = Forms::createFormFactory();
        $builder = $factory->createBuilder();
        $blockType->buildForm($builder, []);
        $form = $builder->getForm();

        $this->assertTrue($form->has('block_type'));
        $this->assertTrue($form->has('position'));
    }
}
