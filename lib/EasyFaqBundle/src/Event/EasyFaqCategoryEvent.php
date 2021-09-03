<?php

namespace Adeliom\EasyFaqBundle\Event;

use Adeliom\EasyFaqBundle\Entity\BaseCategoryEntity;
use Symfony\Contracts\EventDispatcher\Event;

class EasyFaqCategoryEvent extends Event
{

    public const NAME = "EasyFaq.category.before_render";

    protected $category;
    protected $args;
    protected $template;

    public function __construct(?BaseCategoryEntity $category, $args, $template)
    {
        $this->category = $category;
        $this->args = $args;
        $this->template = $template;
    }

    /**
     * @return BaseCategoryEntity|null
     */
    public function getEntry(): ?BaseCategoryEntity
    {
        return $this->category;
    }

    public function getArgs()
    {
        return $this->args;
    }

    public function setArgs($args)
    {
        $this->args = $args;
    }

    /**
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @return mixed
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }


}
