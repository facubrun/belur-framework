<?php

namespace Belur;

use Closure;

class Router {
    protected array $routes = []; // protected para que se pueda extender la clase

    public function __construct()
    {
        foreach (HttpMethod::cases() as $method) {
            $this->routes[$method->value] = [];
        }
    }

    public function resolve(string $uri, string $method) {

        foreach ($this->routes[$method] as $route) {
            if ($route->matches($uri)) {
                return $route;
            }
        }
        throw new HttpNotFoundException();
    }


    protected function registerRoute(HttpMethod $method, string $uri, Closure $action) {
        $this->routes[$method->value][] = new Route($uri, $action); // almacena la acción para el método GET y la URI dada
    }

    public function get($uri, \Closure $action) {
        $this->registerRoute(HttpMethod::GET, $uri, $action);
    }

    public function post($uri, Closure $action) {
        $this->registerRoute(HttpMethod::POST, $uri, $action);
    }

    public function put($uri, Closure $action) {
        $this->registerRoute(HttpMethod::PUT, $uri, $action);
    }

    public function patch($uri, Closure $action) {
        $this->registerRoute(HttpMethod::PATCH, $uri, $action);
    }

    public function delete($uri, Closure $action) {
        $this->registerRoute(HttpMethod::DELETE, $uri, $action);
    }

}