<?php
declare(strict_types=1);

namespace App\Tests;

use App\Tests\Fixtures\Traits\IdEntity;
use App\Tests\Fixtures\Traits\NameEntity;
use App\Tests\Fixtures\Traits\NameSlugEntity;
use App\Tests\Fixtures\Traits\PublishableEntity;
use App\Tests\Fixtures\Traits\SoftDeletableEntity;
use App\Tests\Fixtures\Traits\StatusEntity;
use App\Tests\Fixtures\Traits\ThreeStateStatusEntity;
use App\Tests\Fixtures\Traits\TimestampableEntity;
use Adeliom\EasyCommonBundle\Enum\ThreeStateStatusEnum;
use PHPUnit\Framework\TestCase;
use DateTime;
use DateTimeImmutable;
use ReflectionClass;

class TraitsTest extends TestCase
{
    public function testEntityIdTrait(): void
    {
        $entity = new IdEntity();
        self::assertNull($entity->getId());

        $ref = new ReflectionClass($entity);
        $prop = $ref->getProperty('id');
        $prop->setAccessible(true);
        $prop->setValue($entity, 42);
        self::assertSame(42, $entity->getId());
    }

    public function testEntityNameTrait(): void
    {
        $entity = new NameEntity();
        $entity->setName('Foo');
        self::assertSame('Foo', $entity->getName());
        self::assertSame('Foo', (string) $entity);
    }

    public function testEntityNameSlugTrait(): void
    {
        $entity = new NameSlugEntity();
        $entity->setName('Foo');
        $entity->setSlug('foo');
        self::assertSame('Foo', $entity->getName());
        self::assertSame('foo', $entity->getSlug());
        self::assertSame('Foo', (string) $entity);
    }

    public function testEntityPublishableTrait(): void
    {
        $entity = new PublishableEntity();
        self::assertTrue($entity->isPublished());

        $entity->setPublishDate(new DateTime('+1 day'));
        self::assertFalse($entity->isPublished());

        $entity->setPublishDate(new DateTime('-1 day'));
        $entity->setUnpublishDate(new DateTime('+1 day'));
        self::assertTrue($entity->isPublished());
    }

    public function testEntitySoftDeletableTrait(): void
    {
        $entity = new SoftDeletableEntity();
        self::assertFalse($entity->isDeleted());

        $ref = new ReflectionClass($entity);
        $prop = $ref->getProperty('deletedAt');
        $prop->setAccessible(true);
        $prop->setValue($entity, new DateTime());
        self::assertTrue($entity->isDeleted());

        $entity->recover();
        self::assertFalse($entity->isDeleted());
    }

    public function testEntityStatusTrait(): void
    {
        $entity = new StatusEntity();
        self::assertFalse($entity->getStatus());
        $entity->setStatus(true);
        self::assertTrue($entity->getStatus());
    }

    public function testEntityThreeStateStatusTrait(): void
    {
        $entity = new ThreeStateStatusEntity();
        self::assertSame(ThreeStateStatusEnum::UNPUBLISHED, $entity->getState());
        self::assertTrue($entity->isState(ThreeStateStatusEnum::UNPUBLISHED));
        $entity->setState(ThreeStateStatusEnum::PUBLISHED);
        self::assertTrue($entity->isState(ThreeStateStatusEnum::PUBLISHED));
    }

    public function testEntityTimestampableTrait(): void
    {
        $entity = new TimestampableEntity();
        self::assertInstanceOf(DateTimeImmutable::class, $entity->getCreatedAt());
        self::assertInstanceOf(DateTime::class, $entity->getUpdatedAt());

        $future = new DateTimeImmutable('+1 day');
        $entity->setUpdatedAt($future);
        self::assertSame($future, $entity->getUpdatedAt());
    }
}

