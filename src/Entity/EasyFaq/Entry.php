<?php

namespace App\Entity\EasyFaq;

use Adeliom\EasyFaqBundle\Entity\EntryEntity as BaseEntryEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EasyFaq\EntryRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Entry extends BaseEntryEntity
{

}
