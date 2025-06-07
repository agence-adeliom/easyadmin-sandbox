<?php
declare(strict_types=1);

namespace App\Tests\EasyBlockBundle\Repository;

use App\Entity\EasyBlock\Block;
use App\Repository\EasyBlock\BlockRepository;
use App\Tests\EasyBlockBundle\Fixtures\DummyBlockType;
use App\Tests\EasyBlockBundle\Fixtures\TestBlockFixtures;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BlockRepositoryTest extends KernelTestCase
{
    public function testRepositoryQueries(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $em = $container->get('doctrine')->getManager();
        $schemaTool = new SchemaTool($em);
        $schemaTool->dropSchema([$em->getClassMetadata(Block::class)]);
        $schemaTool->createSchema([$em->getClassMetadata(Block::class)]);

        $fixture = new TestBlockFixtures();
        $fixture->load($em);

        /** @var BlockRepository $repo */
        $repo = $em->getRepository(Block::class);

        $this->assertCount(1, $repo->getActive());
        $this->assertCount(1, $repo->getByType(DummyBlockType::class));
    }
}
