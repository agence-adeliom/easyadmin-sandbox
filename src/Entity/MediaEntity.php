<?php

namespace App\Entity;

use App\Repository\MediaEntityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MediaEntityRepository::class)
 */
class MediaEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="easy_media_type", nullable=true)
     */
    private $file;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $text;

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


}
