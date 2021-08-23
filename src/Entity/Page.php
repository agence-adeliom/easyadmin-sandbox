<?php

namespace App\Entity;

use Adeliom\EasyPageBundle\Entity\BasePageEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Page extends BasePageEntity
{
    /**
     * @var array|null
     * @Groups("main")
     * @ORM\Column(name="content", type="json", nullable=true)
     * @Assert\Type("array")
     */
    protected $content = [];

    /**
     * @return array|null
     */
    public function getContent(): ?array
    {
        return $this->content;
    }

    /**
     * @param array|null $content
     */
    public function setContent(?array $content): void
    {
        $this->content = $content;
    }


}
