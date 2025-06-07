<?php
declare(strict_types=1);

namespace App\Tests\Fixtures;

use Adeliom\EasyCommonBundle\Enum\ThreeStateStatusEnum;
use App\Entity\EasyFaq\Category;
use App\Entity\EasyFaq\Entry;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FaqFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $category = new Category();
        $category->setName('Faq Category');
        $category->setSlug('faq-category');
        $category->setStatus(true);

        $entry = new Entry();
        $entry->setName('Faq Entry');
        $entry->setSlug('faq-entry');
        $entry->setAnswer('Answer');
        $entry->setCategory($category);
        $entry->setState(ThreeStateStatusEnum::PUBLISHED);
        $entry->setPublishDate(new \DateTimeImmutable('-1 day'));

        $manager->persist($category);
        $manager->persist($entry);
        $manager->flush();
    }
}
