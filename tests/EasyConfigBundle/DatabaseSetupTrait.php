<?php
declare(strict_types=1);

namespace App\Tests\EasyConfigBundle;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;

trait DatabaseSetupTrait
{
    private string $originalDsn = '';

    protected function bootKernelWithMemoryDatabase(): EntityManagerInterface
    {
        $this->originalDsn = $_ENV['DATABASE_URL'] ?? (string) getenv('DATABASE_URL');
        putenv('DATABASE_URL=sqlite:///:memory:');
        $_ENV['DATABASE_URL'] = 'sqlite:///:memory:';
        $_SERVER['DATABASE_URL'] = 'sqlite:///:memory:';

        self::bootKernel();
        $em = static::getContainer()->get('doctrine')->getManager();
        $this->initSchema($em);

        return $em;
    }

    protected function restoreDatabaseEnv(): void
    {
        if ($this->originalDsn !== '') {
            putenv('DATABASE_URL='.$this->originalDsn);
            $_ENV['DATABASE_URL'] = $this->originalDsn;
            $_SERVER['DATABASE_URL'] = $this->originalDsn;
        }
    }

    private function initSchema(EntityManagerInterface $em): void
    {
        $tool = new SchemaTool($em);
        $metadata = $em->getMetadataFactory()->getAllMetadata();
        $tool->dropSchema($metadata);
        $tool->createSchema($metadata);
    }
}
