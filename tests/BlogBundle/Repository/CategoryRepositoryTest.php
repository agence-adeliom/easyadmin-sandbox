<?php

namespace App\Tests\BlogBundle\Repository;

use App\Entity\EasyBlog\Category;
use App\Repository\EasyBlog\CategoryRepository;
use App\Tests\BlogBundle\BlogTestCase;
use App\Tests\BlogBundle\SimpleManagerRegistry;

class CategoryRepositoryTest extends BlogTestCase
{
    public function testGetPublished(): void
    {
        $repo = new CategoryRepository(new \App\Tests\BlogBundle\SimpleManagerRegistry($this->em));
        $result = $repo->getPublished();
        $this->assertCount(1, $result);
    }

    public function testGetBySlug(): void
    {
        $repo = new CategoryRepository(new \App\Tests\BlogBundle\SimpleManagerRegistry($this->em));
        $cat = $repo->getBySlug('cat');
        $this->assertInstanceOf(Category::class, $cat);
    }
}
