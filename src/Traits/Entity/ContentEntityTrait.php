<?php

declare(strict_types=1);

namespace App\Traits\Entity;

use Doctrine\ORM\Mapping as ORM;

trait ContentEntityTrait
{
    /**
     * @var array<int, mixed>
     */
    #[ORM\Column(type: 'easy_editor_type', nullable: true)]
    private array $content = [];

    /**
     * @return array<int, mixed>
     */
    public function getContent(): array
    {
        return $this->content;
    }

    /**
     * @param array<int, mixed> $content
     */
    public function setContent(array $content): void
    {
        $this->content = $content;
    }
}
