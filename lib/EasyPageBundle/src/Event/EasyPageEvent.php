<?php

namespace Adeliom\EasyPageBundle\Event;

use Adeliom\EasyPageBundle\Entity\BasePageEntity;
use Symfony\Contracts\EventDispatcher\Event;

class EasyPageEvent extends Event
{

    public const NAME = "easypage.before_render";

    protected $page;
    protected $args;
    protected $template;

    public function __construct(BasePageEntity $page, $args, $template)
    {
        $this->page = $page;
        $this->args = $args;
        $this->template = $template;
    }

    /**
     * @return BasePageEntity
     */
    public function getPage(): BasePageEntity
    {
        return $this->page;
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
