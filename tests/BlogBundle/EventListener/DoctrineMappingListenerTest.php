<?php

namespace App\Tests\BlogBundle\EventListener;

use Adeliom\EasyBlogBundle\EventListener\DoctrineMappingListener;
use App\Entity\EasyBlog\Category;
use App\Entity\EasyBlog\Post;
use App\Tests\BlogBundle\BlogTestCase;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;

class DoctrineMappingListenerTest extends BlogTestCase
{
    public function testLoadClassMetadata(): void
    {
        $listener = new DoctrineMappingListener(Post::class, Category::class);

        $metaPost = new ClassMetadata(Post::class);
        $argsPost = new LoadClassMetadataEventArgs($metaPost, $this->em);
        $listener->loadClassMetadata($argsPost);
        $this->assertTrue($metaPost->hasAssociation('category'));

        $metaCategory = new ClassMetadata(Category::class);
        $argsCategory = new LoadClassMetadataEventArgs($metaCategory, $this->em);
        $listener->loadClassMetadata($argsCategory);
        $this->assertTrue($metaCategory->hasAssociation('posts'));
    }
}
