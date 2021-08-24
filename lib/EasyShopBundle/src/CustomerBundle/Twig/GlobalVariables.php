<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\CustomerBundle\Twig;

final class GlobalVariables
{
    /**
     * @var string
     */
    private $profileTemplate;

    public function __construct(string $profileTemplate)
    {
        $this->profileTemplate = $profileTemplate;
    }

    public function getProfileTemplate(): string
    {
        return $this->profileTemplate;
    }
}
