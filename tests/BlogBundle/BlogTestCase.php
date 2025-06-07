<?php

namespace App\Tests\BlogBundle;

use App\Tests\Fixtures\BlogFixtures;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Adeliom\EasyMediaBundle\Types\EasyMediaType;
use Adeliom\EasyBlogBundle\EventListener\DoctrineMappingListener;
use App\Entity\EasyBlog\Post;
use App\Entity\EasyBlog\Category;

abstract class BlogTestCase extends KernelTestCase
{
    protected EntityManagerInterface $em;

    protected function setUp(): void
    {
        $config = Setup::createAttributeMetadataConfiguration([
            __DIR__ . '/../../src/Entity/EasyBlog',
            __DIR__ . '/../../lib/easy-blog-bundle/src/Entity',
            __DIR__ . '/../../lib/easy-seo-bundle/src/Entity',
            __DIR__ . '/../../lib/easy-media-bundle/src/Entity',
        ], true);

        if (!Type::hasType('easy_media_type')) {
            Type::addType('easy_media_type', EasyMediaType::class);
        }

        $this->em = EntityManager::create(['driver' => 'pdo_sqlite', 'memory' => true], $config);

        $this->em->getEventManager()->addEventListener(
            \Doctrine\ORM\Events::loadClassMetadata,
            new DoctrineMappingListener(Post::class, Category::class)
        );

        $tool = new SchemaTool($this->em);
        $tool->createSchema($this->em->getMetadataFactory()->getAllMetadata());

        (new BlogFixtures())->load($this->em);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->em->clear();
    }
}
