<?php
declare(strict_types=1);

namespace App\Tests\EasyBlockBundle\Block;

use Adeliom\EasyBlockBundle\Block\BlockCollection;
use App\Tests\EasyBlockBundle\Fixtures\DummyBlockType;
use Doctrine\ORM\EntityManagerInterface;
use App\Tests\EasyBlockBundle\Fixtures\AnotherDummyBlockType;
use PHPUnit\Framework\TestCase;

class BlockCollectionTest extends TestCase
{
    public function testBlocksAreIndexedByClass(): void
    {
        $manager = $this->createStub(EntityManagerInterface::class);
        $alpha = new DummyBlockType($manager);
        $omega = new AnotherDummyBlockType($manager);

        $collection = new BlockCollection([$alpha, $omega]);

        $blocks = $collection->getBlocks();
        $this->assertArrayHasKey(AnotherDummyBlockType::class, $blocks);
        $this->assertSame($omega, $blocks[AnotherDummyBlockType::class]);
        $this->assertArrayHasKey(DummyBlockType::class, $blocks);
        $this->assertSame($alpha, $blocks[DummyBlockType::class]);
    }
}
