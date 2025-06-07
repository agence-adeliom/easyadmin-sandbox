<?php
declare(strict_types=1);

namespace App\Tests\EasyBlockBundle\Block;

use Adeliom\EasyBlockBundle\Block\BlockCollection;
use Adeliom\EasyBlockBundle\Block\Helper;
use App\Tests\EasyBlockBundle\Fixtures\DummyBlockType;
use App\Entity\EasyBlock\Block;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\Forms;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

class HelperTest extends TestCase
{
    public function testRenderEasyBlockAndIncludeAssets(): void
    {
        $twig = new Environment(new ArrayLoader(['dummy.html.twig' => '{{ block.key }}']));
        $dispatcher = new EventDispatcher();
        $manager = $this->createMock(EntityManagerInterface::class);
        $repository = $this->createMock(\Doctrine\Persistence\ObjectRepository::class);
        $block = new Block();
        $block->setName('b');
        $block->setKey('alpha-key');
        $block->setType(DummyBlockType::class);
        $block->setStatus(true);
        $block->setSettings(['name' => 'Alpha']);
        $repository->method('find')->willReturn($block);
        $manager->method('getRepository')->willReturn($repository);

        $blockType = new DummyBlockType($manager);
        $collection = new BlockCollection([$blockType]);

        $helper = new class($twig, $dispatcher, $collection, $manager, Block::class, Forms::createFormFactory()) extends Helper {
            public function transformSettingsWithBlockTypeFormBuild($blockType, $block, $defaultSetting)
            {
                return array_merge($defaultSetting, $block->getSettings());
            }
        };

        $result = $helper->renderEasyBlock($twig, [], ['class' => Block::class, 'id' => 1]);
        $this->assertSame('alpha-key', (string) $result);

        $assets = $helper->includeAssets();
        $this->assertStringContainsString('<script', $assets);
    }
}
