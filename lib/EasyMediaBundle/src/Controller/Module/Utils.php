<?php
namespace Adeliom\EasyMediaBundle\Controller\Module;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

trait Utils
{
    /**
     * helper to paginate array.
     *
     * @param [type] $items
     * @param int    $perPage
     * @param [type] $page
     */
    public function paginate($items, $perPage = 10, $page = null)
    {
        $pageName = 'page';
        //$request = Request::createFromGlobals();
        //$currentPage = (int) app('request')->get('page', $default = '0');

        //$page     = $page ?: (Paginator::resolveCurrentPage($pageName) ?: 1);
        $page     = $page ?: 1;
        $total = count( $items ); //total items in array
        $totalPages = ceil( $total/ $perPage ); //calculate total pages
        $page = max($page, 1); //get 1 page when $_GET['page'] <= 0
        $page = min($page, $totalPages); //get last page when $_GET['page'] > $totalPages
        $offset = ($page - 1) * $perPage;
        if( $offset < 0 ) $offset = 0;


        return [
            'data' => array_slice( $items, $offset, $perPage ),
            'total' => $total,
            'from' => $offset,
            'to' => $offset + $perPage,
            'per_page' => $perPage,
            'last_page' => $totalPages,
        ];
        /*new LengthAwarePaginator(
            $items->forPage($page, $perPage)->values(),
            $items->count(),
            $perPage,
            $page,
            [
                'path'     => Paginator::resolveCurrentPath(),
                'pageName' => $pageName,
            ]
        );*/
    }

    /**
     * sanitize input.
     *
     * @return [type] [description]
     */
    protected function getRandomString()
    {
        return call_user_func($this->sanitizedText);
    }

    protected function cleanName($text, $folder = false)
    {
        $pattern = $this->filePattern($folder ? $this->folderChars : $this->fileChars);
        $text    = preg_replace($pattern, '', $text);

        return $text ?: $this->getRandomString();
    }

    protected function filePattern($item)
    {
        return '/(script.*?\/script)|[^(' . $item . ')a-zA-Z0-9]+/ius';
    }

    protected function getItemTime($time)
    {
        return $time ? (new \DateTime("@$time"))->format($this->LMF) : null;
    }

    /**
     * resolve url for "file/dir path" instead of laravel builtIn.
     * which needs to make extra call just to resolve the url.
     *
     * @param [type] $path [description]
     *
     * @return [type] [description]
     */
    protected function resolveUrl($path)
    {
        return $this->clearDblSlash("{$this->baseUrl}/{$path}");
    }

    protected function clearDblSlash($str)
    {
        $str = preg_replace('/\/+/', '/', $str);
        $str = str_replace(':/', '://', $str);

        return $str;
    }
}
