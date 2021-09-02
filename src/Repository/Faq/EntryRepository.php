<?php

namespace App\Repository\Faq;

use Adeliom\EasyFaqBundle\Repository\BaseEntryRepository;
use App\Entity\Faq\Entry;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @method Entry|null find($id, $lockMode = null, $lockVersion = null)
 * @method Entry|null findOneBy(array $criteria, array $orderBy = null)
 * @method Entry[]    findAll()
 * @method Entry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntryRepository extends BaseEntryRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Entry::class);
    }
}
