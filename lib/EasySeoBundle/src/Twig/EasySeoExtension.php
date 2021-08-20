<?php


namespace Adeliom\EasySeoBundle\Twig;

use Adeliom\EasySeoBundle\Entity\SEO;
use Adeliom\EasySeoBundle\Services\BreadCrumbCollection;
use League\Flysystem\FileAttributes;
use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use League\MimeTypeDetection\FinfoMimeTypeDetector;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\Markup;
use Twig\TwigFilter;
use Twig\TwigFunction;

class EasySeoExtension extends AbstractExtension implements GlobalsInterface
{
    /**
     * @var Environment
     */
    protected $twig;
    protected $breadcrumb;

    protected $titleConfig;
    protected $breadcrumbConfig;

    public function __construct(Environment $twig, BreadCrumbCollection $breadcrumb, $titleConfig, $breadcrumbConfig)
    {
        $this->twig = $twig;
        $this->breadcrumb = $breadcrumb;
        $this->titleConfig = $titleConfig;
        $this->breadcrumbConfig = $breadcrumbConfig;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('seo_metas', [$this, 'renderSeoMetas']),
            new TwigFunction('seo_title', [$this, 'renderSeoTitle']),
            new TwigFunction('seo_breadcrumb', [$this, 'renderBreadcrumb']),
        ];
    }

    public function getGlobals(): array
    {
        return [
            'easy_seo_title' => $this->titleConfig,
            'easy_seo_breadcrumb' => $this->breadcrumbConfig,
        ];
    }
    public function renderBreadcrumb()
    {
        return new Markup($this->twig->render('@EasySeo/block-breadcrumb.html.twig', ["data" => $this->breadcrumb->getItems()]), 'UTF-8');
    }

    public function renderSeoTitle($seo){
        $title = "";
        if(is_string($seo)){
            $title = $seo;
        }
        if($seo instanceof SEO){
            $title = $seo->title;
        }

        if(!empty($this->titleConfig["suffix"])){
            $title = sprintf("%s %s %s", $title, $this->titleConfig["separator"], $this->titleConfig["suffix"]);
        }

        return $title;
    }

    public function renderSeoMetas(SEO $seo){
        return new Markup($this->twig->render('@EasySeo/block-metas.html.twig', ["data" => $seo]), 'UTF-8');
    }
}
