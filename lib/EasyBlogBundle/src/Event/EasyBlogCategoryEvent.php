<?php

namespace Adeliom\EasyBlogBundle\Event;

use Adeliom\EasyBlogBundle\Entity\BaseCategoryEntity;
use Symfony\Contracts\EventDispatcher\Event;

class EasyBlogCategoryEvent extends Event
{

    public const NAME = "easyblog.category.before_render";

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
    public function getPost(): ?BaseCategoryEntity
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
