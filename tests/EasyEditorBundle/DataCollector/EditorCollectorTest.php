<?php

declare(strict_types=1);

namespace App\Tests\EasyEditorBundle\DataCollector;

use Adeliom\EasyEditorBundle\Block\BlockCollection;
use Adeliom\EasyEditorBundle\Block\Helper;
use Adeliom\EasyEditorBundle\DataCollector\EditorCollector;
use App\Tests\EasyEditorBundle\Fixtures\DummyBlock;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

final class EditorCollectorTest extends TestCase
{
    public function testCollectStoresBlockTraces(): void
    {
        $twig = new Environment(new ArrayLoader([
            'dummy.html.twig' => 'dummy',
        ]));
        $dispatcher = new EventDispatcher();
        $manager = $this->createMock(EntityManagerInterface::class);
        $block = new DummyBlock($manager);
        $collection = new BlockCollection([$block]);
        $formFactory = Forms::createFormFactoryBuilder()->getFormFactory();
        $helper = new Helper($twig, $dispatcher, $collection, $formFactory, $manager);
        $helper->renderEasyEditorBlock($twig, [], ['block_type' => $block::class]);

        $collector = new EditorCollector($helper);
        $collector->collect(new Request(), new Response());

        self::assertNotEmpty($collector->getBlocks());
        self::assertSame(EditorCollector::class, $collector->getName());
    }
}
