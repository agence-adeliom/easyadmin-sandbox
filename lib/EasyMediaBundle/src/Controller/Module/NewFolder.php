<?php
namespace Adeliom\EasyMediaBundle\Controller\Module;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

trait NewFolder
{
    /**
     * create new folder.
     *
     * @param Request $request [description]
     *
     * @return [type] [description]
     */
    public function createNewFolder(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $path            = $data["path"];
        $new_folder_name = $this->cleanName($data["new_folder_name"], true);
        $full_path       = !$path ? $new_folder_name : $this->clearDblSlash("$path/$new_folder_name");
        $message         = '';

        if ($this->filesystem->fileExists($full_path)) {
            $message = $this->translator->trans('MediaManager::messages.error.already_exists');
        } elseif (!$this->filesystem->createDirectory($full_path)) {
            $message = $this->translator->trans('MediaManager::messages.error.creating_dir');
        }

        return new JsonResponse(compact('message', 'new_folder_name'));
    }

}
