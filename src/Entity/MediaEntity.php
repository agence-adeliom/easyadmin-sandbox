<?php

namespace App\Entity;

use App\Repository\MediaEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MediaEntityRepository::class)]
class MediaEntity implements \Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: 'easy_media_type', nullable: true)]
    #[Assert\NotBlank]
    private $file;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::TEXT, nullable: true)]
    private ?string $text = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 50, nullable: true)]
    private ?string $icon = null;

    /**
     * @var \Doctrine\Common\Collections\Collection<int, \App\Entity\Article>
     */
    #[ORM\OneToMany(targetEntity: Article::class, mappedBy: 'media')]
    private \Doctrine\Common\Collections\Collection $articles;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::JSON, nullable: true)]
    #[Assert\Type('array')]
    protected ?array $data = null;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file): self
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return MediaEntity
     */
    public function setText(mixed $text)
    {
        $this->text = $text;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    /**
     * @return MediaEntity
     */
    public function setIcon(?string $icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setMedia($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        // set the owning side to null (unless already changed)
        if ($this->articles->removeElement($article) && $article->getMedia() === $this) {
            $article->setMedia(null);
        }

        return $this;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function setData(?array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function __toString(): string
    {
        return sprintf('MediaEntity #%s', $this->getId());
    }
}
