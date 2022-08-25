<?php

namespace App\Entity\EasyPage;

use Adeliom\EasyPageBundle\Entity\Page as BasePage;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: \App\Repository\EasyPage\PageRepository::class)]
#[ORM\Table(name: 'easy_page__page')]
#[ORM\HasLifecycleCallbacks]
class Page extends BasePage
{
    /**
     * @var array|null
     */
    #[Groups('main')]
    #[ORM\Column(name: 'content', type: \Doctrine\DBAL\Types\Types::JSON, nullable: true)]
    #[Assert\Type('array')]
    protected $content = [];

    #[Groups('main')]
    #[ORM\Column(name: 'embed', type: \Doctrine\DBAL\Types\Types::STRING, nullable: true)]
    #[Assert\Url]
    #[Assert\Valid]
    protected ?string $embed = null;

    public function getContent(): ?array
    {
        return $this->content;
    }

    public function setContent(?array $content): void
    {
        $this->content = $content;
    }

    public function getEmbed(): ?string
    {
        return $this->embed;
    }

    public function setEmbed(?string $embed): void
    {
        $this->embed = $embed;
    }
}
