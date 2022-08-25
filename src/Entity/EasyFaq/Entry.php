<?php

namespace App\Entity\EasyFaq;

use Adeliom\EasyFaqBundle\Entity\EntryEntity as BaseEntryEntity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: \App\Repository\EasyFaq\EntryRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'easy_faq__entry')]
#[ORM\Index(name: 'easy_faq_indexes', columns: ['state'])]
class Entry extends BaseEntryEntity
{
}
