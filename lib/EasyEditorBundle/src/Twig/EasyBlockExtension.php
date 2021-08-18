<?php

namespace Adeliom\EasyEditorBundle\Twig;

use Adeliom\EasyEditorBundle\Block\AbstractBlock;
use Adeliom\EasyEditorBundle\Editor\Editor;
use Adeliom\EasyEditorBundle\Editor\EditorConfig;
use Adeliom\EasyEditorBundle\Editor\EditorConfigCollection;
use Adeliom\EasyEditorBundle\Form\EditorjsTransformer;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class EasyBlockExtension extends AbstractExtension
{
    /**
     * @var Environment
     */
    private $rendererEngine;

    public function __construct(Environment $rendererEngine)
    {
        $this->rendererEngine = $rendererEngine;
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('render_easyblock', [$this, 'renderEasyBlock'], ['is_safe' => ['js', 'html']]),
        ];
    }

    /**
     * @param array $datas
     */
    public function renderEasyBlock($datas)
    {

    }

}
