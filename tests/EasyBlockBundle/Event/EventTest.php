<?php
declare(strict_types=1);

namespace App\Tests\EasyBlockBundle\Event;

use Adeliom\EasyBlockBundle\Event\BlockEvent;
use Adeliom\EasyBlockBundle\Event\ParseBlockEvent;
use Adeliom\EasyBlockBundle\Event\PostBlockEvent;
use Adeliom\EasyBlockBundle\Event\PreBlockEvent;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    public function testParseBlockEvent(): void
    {
        $event = new ParseBlockEvent(['foo' => ['bar']]);
        $this->assertSame(['foo' => ['bar']], $event->getSettings());
        $this->assertSame(['bar'], $event->getSetting('foo'));
    }

    public function testPostBlockEvent(): void
    {
        $event = new PostBlockEvent('content');
        $this->assertSame('content', $event->getContent());
    }

    public function testPreBlockEvent(): void
    {
        $collection = new ArrayCollection();
        $event = new PreBlockEvent($collection);
        $this->assertSame($collection, $event->getBlocks());
    }

    public function testBlockEventIsSubclass(): void
    {
        $event = new BlockEvent();
        $this->assertInstanceOf(BlockEvent::class, $event);
    }
}
