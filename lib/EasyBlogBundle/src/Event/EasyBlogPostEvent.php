<?php

namespace Adeliom\EasyBlogBundle\Event;

use Adeliom\EasyBlogBundle\Entity\BasePostEntity;
use Symfony\Contracts\EventDispatcher\Event;

class EasyBlogPostEvent extends Event
{

    public const NAME = "easyblog.post.before_render";

    protected $post;
    protected $args;
    protected $template;

    public function __construct(BasePostEntity $post, $args, $template)
    {
        $this->post = $post;
        $this->args = $args;
        $this->template = $template;
    }

    /**
     * @return BasePostEntity
     */
    public function getPost(): BasePostEntity
    {
        return $this->post;
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
