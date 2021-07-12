<?php

namespace Adeliom\EasyMediaBundle\Controller;

use Adeliom\EasyMediaBundle\Controller\Module\Delete;
use Adeliom\EasyMediaBundle\Controller\Module\Download;
use Adeliom\EasyMediaBundle\Controller\Module\GetContent;
use Adeliom\EasyMediaBundle\Controller\Module\GlobalSearch;
use Adeliom\EasyMediaBundle\Controller\Module\Lock;
use Adeliom\EasyMediaBundle\Controller\Module\Metas;
use Adeliom\EasyMediaBundle\Controller\Module\Move;
use Adeliom\EasyMediaBundle\Controller\Module\NewFolder;
use Adeliom\EasyMediaBundle\Controller\Module\Rename;
use Adeliom\EasyMediaBundle\Controller\Module\Upload;
use Adeliom\EasyMediaBundle\Controller\Module\Utils;
use Adeliom\EasyMediaBundle\Controller\Module\Visibility;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;


class MediaController extends AbstractController
{
    use Utils,
        GetContent,
        Delete,
        Download,
        Lock,
        Move,
        Rename,
        Metas,
        Upload,
        NewFolder,
        Visibility,
        GlobalSearch;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var string
     */
    protected $rootPath;
    protected $baseUrl;
    protected $ignoreFiles;
    protected $GFI;
    protected $LMF;
    protected $paginationAmount;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    public function __construct(Container $container, TranslatorInterface $translator, EventDispatcherInterface $eventDispatcher, EntityManagerInterface $em)
    {
        $this->rootPath = $container->getParameter("adeliom_easymedia.storage");
        $this->baseUrl = $container->getParameter("adeliom_easymedia.base_url");
        $this->fileChars = $container->getParameter("adeliom_easymedia.allowed_fileNames_chars");
        $this->folderChars = $container->getParameter("adeliom_easymedia.allowed_folderNames_chars");
        $this->sanitizedText = $container->getParameter("adeliom_easymedia.sanitized_text");
        $this->lockEntity = $container->getParameter("adeliom_easymedia.lock_entity");
        $this->metasEntity = $container->getParameter("adeliom_easymedia.metas_entity");
        $this->metasService = $container->get("adeliom_easymedia.service.metas");
        $this->ignoreFiles = $container->getParameter("adeliom_easymedia.ignore_files");
        $this->GFI = $container->getParameter("adeliom_easymedia.get_folder_info");
        $this->LMF = $container->getParameter("adeliom_easymedia.last_modified_format");
        $this->paginationAmount = $container->getParameter("adeliom_easymedia.pagination_amount");
        $adapter = new LocalFilesystemAdapter($this->rootPath);
        $this->filesystem = new Filesystem($adapter);
        $this->em = $em;

        $this->locker = $em->getRepository($this->lockEntity);
        $this->metas = $em->getRepository($this->metasEntity);

        $this->unallowedMimes = $container->getParameter("adeliom_easymedia.unallowed_mimes");
        $this->unallowedExt = $container->getParameter("adeliom_easymedia.unallowed_ext");
        $this->eventDispatcher = $eventDispatcher;
        $this->translator = $translator;
    }

    /**
     * main view.
     *
     * @return [type] [description]
     */
    public function index()
    {
        $datas = [];
        return $this->render("@EasyMedia/manager_view.html.twig", $datas);
    }

    public function browse(Request $request)
    {
        $data = [
            "provider" => $request->query->get("provider"),
            "restrict" => $request->query->get("restrict"),
            "CKEditor" => $request->query->get("CKEditor"),
            "CKEditorFuncNum" => $request->query->get("CKEditorFuncNum"),
            "langCode" => $request->query->get("langCode", "en")
        ];
        return $this->render("@EasyMedia/browser.html.twig", $data);
    }


}
