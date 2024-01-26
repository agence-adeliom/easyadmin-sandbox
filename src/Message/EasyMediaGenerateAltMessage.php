<?php

declare(strict_types=1);

namespace App\Message;

class EasyMediaGenerateAltMessage
{
    private int $mediaId;

    public function __construct(int $mediaId)
    {
        $this->mediaId = $mediaId;
    }

    public function getMediaId(): int
    {
        return $this->mediaId;
    }
}
