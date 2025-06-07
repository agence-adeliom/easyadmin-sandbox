<?php
declare(strict_types=1);

namespace App\Tests\EasyBlockBundle\Controller;

use Adeliom\EasyBlockBundle\Block\BlockCollection;
use Adeliom\EasyBlockBundle\Controller\BlockCrudController;
use PHPUnit\Framework\TestCase;

class BlockCrudControllerTest extends TestCase
{
    public function testSubscribedServicesContainsBlockCollection(): void
    {
        $services = BlockCrudController::getSubscribedServices();
        $this->assertArrayHasKey('easy_block.block_collection', $services);
        $this->assertSame('?' . BlockCollection::class, $services['easy_block.block_collection']);
    }
}
