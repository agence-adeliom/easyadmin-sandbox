<?php

declare(strict_types=1);

namespace App\Tests\EasyEditorBundle\Maker;

use Adeliom\EasyEditorBundle\Maker\MakeBlock;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\MakerBundle\InputConfiguration;

final class MakeBlockTest extends TestCase
{
    public function testCommandBasics(): void
    {
        self::assertSame('make:block', MakeBlock::getCommandName());
        self::assertSame('Creates a new block type', MakeBlock::getCommandDescription());

        $maker = new MakeBlock();
        $command = new Command('test');
        $maker->configureCommand($command, new InputConfiguration());

        $definition = $command->getDefinition();
        self::assertTrue($definition->hasArgument('block-type'));
    }
}
