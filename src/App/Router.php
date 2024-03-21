<?php

namespace App;

class Router
{
    private array $routes;
    private View $view;
    public function __construct(public readonly array $request)
    {
        $this->loadRoutes();
        $this->callController();
    }

    private function loadRoutes(): void
    {
        $this->routes = include 'routes.php';
    }

    private function callController(): void
    {
        foreach ($this->routes as $route_name => $info) {
            $uri = $info['uri'];
            if(preg_match("/$uri/", $this->request['uri'], $matches)) {
                unset($matches[0]);
                $parsed_controller = $this->parseController($info['controller']);
                $instance = new $parsed_controller[0]();
                $method = $parsed_controller[1];
                $this->view = empty($matches) ? $instance->$method() : $instance->$method(...$matches);
            }
        }
    }

    public function renderResponse(): void
    {
        echo $this->view->render();
    }

    private function parseController(string $string): array
    {
        return explode('@', $string);
    }
}