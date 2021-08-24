<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Transformer;

/**
 * The pool stored a group of available payment method.
 */
class Pool
{
    /**
     * @var array
     */
    protected $transformer = [];

    /**
     * Add a transformer into into the pool.
     *
     * @param string $type
     */
    public function addTransformer($type, BaseTransformer $instance): void
    {
        $this->methods[$type] = $instance;
    }

    /**
     * @return array of transformer methods
     */
    public function getTransformers()
    {
        return $this->methods;
    }

    /**
     * return a Transformer Object.
     *
     * @param string $type
     *
     * @return BaseTransformer
     */
    public function getTransformer($type)
    {
        return $this->methods[$type] ?? null;
    }
}
