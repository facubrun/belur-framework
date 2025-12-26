<?php

namespace Belur;

class Router {
    protected array $routes = []; // protected para que se pueda extender la clase

    public function __construct()
    {
        foreach (HttpMethod::cases() as $method) {
            $this->routes[$method->value] = [];
        }
    }

    public function resolve() {
        $method = $_SERVER['REQUEST_METHOD']; // obtiene el método HTTP de la solicitud
        $uri = $_SERVER['REQUEST_URI']; // obtiene la URI de la solicitud

        $action = $this->routes[$method][$uri] ?? null; // busca la acción correspondiente al método y URI

        if (is_null($action)){
            throw new HttpNotFoundException();
        }

        return $action; // devuelve la acción encontrada
    }


    public function get($uri, callable $action) {
        $this->routes[HttpMethod::GET->value][$uri] = $action; // almacena la acción para el método GET y la URI dada
    }

    public function post($uri, callable $action) {
        $this->routes[HttpMethod::POST->value][$uri] = $action; // almacena la acción para el método POST y la URI dada
    }

    public function put($uri, callable $action) {
        $this->routes[HttpMethod::PUT->value][$uri] = $action; // almacena la acción para el método PUT y la URI dada
    }

    public function patch($uri, callable $action) {
        $this->routes[HttpMethod::PATCH->value][$uri] = $action; // almacena la acción para el método PATCH y la URI dada
    }

    public function delete($uri, callable $action) {
        $this->routes[HttpMethod::DELETE->value][$uri] = $action; // almacena la acción para el método DELETE y la URI dada
    }

}