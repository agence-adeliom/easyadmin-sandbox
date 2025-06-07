<?php
declare(strict_types=1);

namespace App\Tests\EasyBlockBundle\Maker;

use Adeliom\EasyBlockBundle\Maker\MakeSharedBlock;
use PHPUnit\Framework\TestCase;

class MakeSharedBlockTest extends TestCase
{
    public function testCommandNameAndDescription(): void
    {
        $maker = new MakeSharedBlock();
        $this->assertSame('make:block:shared', $maker->getCommandName());
        $this->assertNotEmpty($maker->getCommandDescription());
    }
}
