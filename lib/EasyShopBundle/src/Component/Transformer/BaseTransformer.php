<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Transformer;

use Psr\Log\LoggerInterface;

abstract class BaseTransformer
{
    /**
     * @var the transformer option
     */
    protected $options;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    public function setOptions(array $options): void
    {
        $this->options = $options;
    }

    /**
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed string
     */
    public function getOption($name, $default = null)
    {
        return $this->options[$name] ?? $default;
    }
}
