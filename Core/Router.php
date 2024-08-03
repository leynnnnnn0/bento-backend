<?php

require_once 'Request.php';
class Router
{
    public array $routes = [];
    public function get(string $url, $callback)
    {
        $this->routes[] = [
            'url' => $url,
            'callback' => $callback,
            'method' => 'GET'
        ];
    }

    public function post(string $url, $callback)
    {
        $this->routes[] = [
            'url' => $url,
            'callback' => $callback,
            'method' => 'POST'
        ];
    }

    public function resolve()
    {
        $url = Request::url();
        $method = Request::method();

        foreach ($this->routes as $route) {
            if($route['url'] === $url && $route['method'] === $method) {
                if(is_array($route['callback']))
                {
                    $controller = new $route['callback'][0]();
                    $route['callback'][0] = $controller;
                    return call_user_func($route['callback']);
                }
                return call_user_func($route['callback']);
            }
        }
        return 'NOT FOUND';
    }
}