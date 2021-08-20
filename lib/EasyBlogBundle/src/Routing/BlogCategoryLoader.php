<?php

namespace Adeliom\EasyBlogBundle\Routing;


use Adeliom\EasyBlogBundle\Repository\BaseCategoryRepository;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class BlogCategoryLoader extends Loader
{
    private $isLoaded = false;

    private $controller;
    private $entity;
    private $repository;
    private $config;


    public function __construct(string $controller, string $entity, BaseCategoryRepository $repository, array $config, string $env = null)
    {
        parent::__construct($env);

        $this->controller = $controller;
        $this->config = $config;
        $this->entity = $entity;
        $this->repository = $repository;
    }

    public function load($resource, string $type = null)
    {
        if (true === $this->isLoaded) {
            throw new \RuntimeException('Do not add the "easy_blog_category" loader twice');
        }

        $routes = new RouteCollection();

        // prepare a new route
        $path = $this->config['root_path'] . '/{category}';
        $defaults = [
            '_controller' => $this->controller . '::index',
            'category' => '',
        ];
        $requirements = [
            'category' => "([a-zA-Z0-9_-]+\/?)*",
        ];
        $route = new Route($path, $defaults, $requirements);

        // add the new route to the route collection
        $routeName = 'easy_blog_category_index';
        $routes->add($routeName, $route, -85);

        $this->isLoaded = true;

        return $routes;
    }

    public function supports($resource, string $type = null)
    {
        return 'easy_blog_category' === $type;
    }
}
