<?php
declare(strict_types=1);

namespace App\Tests\EasyBlockBundle\Fixtures;

use Doctrine\ORM\EntityManagerInterface;

class AnotherDummyBlockType extends DummyBlockType
{
    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager);
    }

    public function getName(): string
    {
        return 'Another Dummy';
    }
}
