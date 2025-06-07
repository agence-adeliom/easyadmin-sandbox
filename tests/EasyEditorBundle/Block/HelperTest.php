<?php

declare(strict_types=1);

namespace App\Tests\EasyEditorBundle\Block;

use Adeliom\EasyEditorBundle\Block\BlockCollection;
use Adeliom\EasyEditorBundle\Block\Helper;
use App\Tests\EasyEditorBundle\Fixtures\DummyBlock;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\Forms;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

final class HelperTest extends TestCase
{
    public function testRenderBlockReturnsMarkup(): void
    {
        $twig = new Environment(new ArrayLoader([
            'dummy.html.twig' => '<div id="{{ settings.attr_id }}">dummy</div>',
        ]));
        $dispatcher = new EventDispatcher();
        $manager = $this->createMock(EntityManagerInterface::class);
        $block = new DummyBlock($manager);
        $collection = new BlockCollection([$block]);
        $formFactory = Forms::createFormFactoryBuilder()->getFormFactory();
        $helper = new Helper($twig, $dispatcher, $collection, $formFactory, $manager);

        $markup = $helper->renderEasyEditorBlock($twig, [], ['block_type' => $block::class, 'position' => 1]);

        self::assertStringContainsString('dummy', (string) $markup);
        self::assertNotEmpty($helper->getTraces());
        self::assertIsString($helper->includeAssets());
    }
}
