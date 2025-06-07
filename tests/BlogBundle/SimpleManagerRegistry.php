<?php

namespace App\Tests\BlogBundle;

use Doctrine\Persistence\ConnectionRegistry;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ObjectManager as DoctrineObjectManager;
use Doctrine\ORM\EntityManagerInterface;

class SimpleManagerRegistry implements ManagerRegistry
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function getDefaultConnectionName(): string
    {
        return 'default';
    }

    public function getConnection(?string $name = null): Connection
    {
        return $this->em->getConnection();
    }

    public function getConnections(): array
    {
        return ['default' => $this->em->getConnection()];
    }

    public function getConnectionNames(): array
    {
        return ['default' => 'default'];
    }

    public function getDefaultManagerName(): string
    {
        return 'default';
    }

    public function getManager(?string $name = null): DoctrineObjectManager
    {
        return $this->em;
    }

    public function getManagers(): array
    {
        return ['default' => $this->em];
    }

    public function resetManager(?string $name = null): DoctrineObjectManager
    {
        return $this->em;
    }

    public function getAliasNamespace(string $alias): string
    {
        return '';
    }

    public function getManagerNames(): array
    {
        return ['default' => 'default'];
    }

    public function getRepository(string $persistentObject, ?string $persistentManagerName = null): ObjectRepository
    {
        $metadata = $this->em->getClassMetadata($persistentObject);
        $repositoryClass = $metadata->customRepositoryClassName ?? \Doctrine\ORM\EntityRepository::class;
        if (is_subclass_of($repositoryClass, \Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository::class)) {
            return new $repositoryClass($this);
        }
        return new $repositoryClass($this->em, $metadata);
    }

    public function getManagerForClass(string $class): ?DoctrineObjectManager
    {
        return $this->em;
    }
}
