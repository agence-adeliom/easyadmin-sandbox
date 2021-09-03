<?php

namespace App\Entity\Faq;

use Adeliom\EasyFaqBundle\Entity\BaseEntryEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Faq\EntryRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Entry extends BaseEntryEntity
{

}
