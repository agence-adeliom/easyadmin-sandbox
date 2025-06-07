<?php
declare(strict_types=1);

namespace App\Tests\EasyMenu;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class DoctrineOrmTestCase extends KernelTestCase
{
    protected EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $this->entityManager = self::getContainer()->get('doctrine')->getManager();
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropSchema($this->getMetadata());
        $schemaTool->createSchema($this->getMetadata());
    }

    /**
     * @return array<\Doctrine\ORM\Mapping\ClassMetadata>
     */
    abstract protected function getMetadata(): array;

    protected function tearDown(): void
    {
        $this->entityManager->close();
        parent::tearDown();
    }
}
