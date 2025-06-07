<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $article = new Article();
        $article->setTitle('Test article');
        $manager->persist($article);
        $manager->flush();
    }
}
