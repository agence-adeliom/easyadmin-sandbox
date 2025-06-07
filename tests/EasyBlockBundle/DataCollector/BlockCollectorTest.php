<?php
declare(strict_types=1);

namespace App\Tests\EasyBlockBundle\DataCollector;

use Adeliom\EasyBlockBundle\Block\Helper;
use Adeliom\EasyBlockBundle\DataCollector\BlockCollector;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockCollectorTest extends TestCase
{
    public function testCollectStoresBlocks(): void
    {
        $helper = $this->createMock(Helper::class);
        $helper->method('getTraces')->willReturn(['foo']);

        $collector = new BlockCollector($helper);
        $collector->collect(new Request(), new Response());

        $this->assertSame(['foo'], $collector->getBlocks());
        $this->assertSame(BlockCollector::class, $collector->getName());
    }
}
