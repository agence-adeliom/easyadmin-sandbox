<?php

declare(strict_types=1);

namespace App\Tests\EasyEditorBundle\Block;

use Adeliom\EasyEditorBundle\Block\BlockCollection;
use App\Tests\EasyEditorBundle\Fixtures\DummyBlock;
use App\Tests\EasyEditorBundle\Fixtures\Entity\DummyEntity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use PHPUnit\Framework\TestCase;

final class BlockCollectionTest extends TestCase
{
    private function createBlock(string $template, int $position, bool $supports = true): DummyBlock
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $block = new class($manager, $template, $position, $supports) extends DummyBlock {
            public function __construct(EntityManagerInterface $manager, private string $tpl, private int $pos, private bool $supportsFlag)
            {
                parent::__construct($manager);
            }
            public function getTemplate(): string
            {
                return $this->tpl;
            }
            public function getPosition(): int
            {
                return $this->pos;
            }
            public function supports(string $objectClass, ?object $instance = null): bool
            {
                return $this->supportsFlag;
            }
        };
        return $block;
    }

    public function testGetAllowedBlocksFiltersByType(): void
    {
        $blockA = $this->createBlock('a.html.twig', 2);
        $blockB = $this->createBlock('b.html.twig', 1);
        $collection = new BlockCollection([$blockA, $blockB]);

        $allowed = $collection->getAllowedBlocks([$blockA::class]);
        self::assertCount(1, $allowed);
    }

    public function testEnabledSupportFilterRemovesUnsupportedBlocks(): void
    {
        $supported = $this->createBlock('a.html.twig', 1, true);
        $collection = new BlockCollection([$supported]);

        $metadata = new ClassMetadata(DummyEntity::class);
        $metadata->setIdentifier(['id']);
        $dto = new EntityDto(DummyEntity::class, $metadata, null, new DummyEntity());
        $result = $collection->enabledSupportFilter($dto);

        self::assertSame($collection, $result);
    }
}
