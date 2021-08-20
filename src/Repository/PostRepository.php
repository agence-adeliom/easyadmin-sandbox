<?php

namespace App\Repository;

use Adeliom\EasyBlogBundle\Repository\BasePostRepository;
use App\Entity\Post;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends BasePostRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }
}
