<?php

namespace App\Entity\EasyPage;

use Adeliom\EasyPageBundle\Entity\Page as BasePage;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EasyPage\PageRepository")
 * @ORM\Table(name="easy_page__page")
 * @ORM\HasLifecycleCallbacks()
 */
class Page extends BasePage
{
    /**
     * @var array|null
     * @Groups("main")
     * @ORM\Column(name="content", type="json", nullable=true)
     * @Assert\Type("array")
     */
    protected $content = [];

    /**
     * @var string|null
     * @Groups("main")
     * @ORM\Column(name="embed", type="string", nullable=true)
     * @Assert\Url()
     * @Assert\Valid()
     */
    protected $embed = null;

    /**
     * @return array|null
     */
    public function getContent(): ?array
    {
        return $this->content;
    }

    /**
     * @param array|null $content
     */
    public function setContent(?array $content): void
    {
        $this->content = $content;
    }

    /**
     * @return string|null
     */
    public function getEmbed(): ?string
    {
        return $this->embed;
    }

    /**
     * @param string|null $embed
     */
    public function setEmbed(?string $embed): void
    {
        $this->embed = $embed;
    }




}
