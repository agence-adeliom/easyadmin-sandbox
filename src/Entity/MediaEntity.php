<?php

namespace App\Entity;

use App\Repository\MediaEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MediaEntityRepository::class)]
class MediaEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;
    #[ORM\Column(type: 'easy_media_type', nullable: true)]
    #[Assert\NotBlank]
    private $file;
    #[ORM\Column(type: 'text', nullable: true)]
    private $text;
    #[ORM\OneToMany(targetEntity: Article::class, mappedBy: 'media')]
    private $articles;
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
     * @param mixed $text
     * @return MediaEntity
     */
    public function setText($text)
    {
        $this->text = $text;
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
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getMedia() === $this) {
                $article->setMedia(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return sprintf("MediaEntity #%s", $this->getId());
    }
}
