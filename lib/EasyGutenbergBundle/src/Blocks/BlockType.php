<?php

namespace Adeliom\EasyGutenbergBundle\Blocks;

class BlockType implements BlockTypeInterface
{
    /** @var string */
    public string $name;

    /** @var array */
    public array $attributes;

    /** @var callable */
    public $renderCallback;

    public function __construct(string $name, array $attributes = [], callable $renderCallback = null)
    {
        $this->name = $name;
        $this->attributes = $attributes;
        $this->renderCallback = $renderCallback;
    }

    public function isDynamic(): bool
    {
        return is_callable($this->renderCallback);
    }
}
