<?php
declare(strict_types=1);

namespace App\Tests\EasyFaq;

use Adeliom\EasyFaqBundle\EventListener\DoctrineMappingListener;
use App\Entity\EasyFaq\Category;
use App\Entity\EasyFaq\Entry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;

class DoctrineMappingListenerTest extends KernelTestCase
{
    public function testAssociationsAreAdded(): void
    {
        self::bootKernel();
        $em = self::getContainer()->get('doctrine')->getManager();
        $listener = new DoctrineMappingListener(Entry::class, Category::class);
        $entryMetadata = $em->getClassMetadata(Entry::class);
        $categoryMetadata = $em->getClassMetadata(Category::class);
        $listener->loadClassMetadata(new LoadClassMetadataEventArgs($entryMetadata, $em));
        $listener->loadClassMetadata(new LoadClassMetadataEventArgs($categoryMetadata, $em));
        self::assertTrue($entryMetadata->hasAssociation('category'));
        self::assertTrue($categoryMetadata->hasAssociation('entries'));
    }
}
