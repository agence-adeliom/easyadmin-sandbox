<?php

declare(strict_types=1);

namespace App\Message;

class EasyMediaGenerateAltMessage
{
    public function __construct(private readonly int $mediaId)
    {
    }

    public function getMediaId(): int
    {
        return $this->mediaId;
    }
}
