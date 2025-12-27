<?php

namespace Belur\Routing;

use Belur\Http\HttpMethod;
use Belur\Http\HttpNotFoundException;
use Belur\Http\Request;
use Closure;

/**
 * HTTP Router
 */
class Router {
    protected array $routes = []; // protected para que se pueda extender la clase

    /**
     * Constructor initializes the routes array for each HTTP method.
     */
    public function __construct()
    {
        foreach (HttpMethod::cases() as $method) {
            $this->routes[$method->value] = [];
        }
    }

    /**
     * Resolve the route of the ´$request´.
     *
     * @param Request $request
     * @return Route
     * @throws HttpNotFoundException
     */
    public function resolve(Request $request) {

        foreach ($this->routes[$request->method()->value] as $route) {
            if ($route->matches($request->uri())) {
                return $route;
            }
        }
        throw new HttpNotFoundException();
    }

    /**
     * Register a route for the given HTTP method, URI and action.
     * @param HttpMethod $method
     * @param string $uri
     * @param Closure $action
     * @return void
     */
    protected function registerRoute(HttpMethod $method, string $uri, Closure $action) {
        $this->routes[$method->value][] = new Route($uri, $action); // almacena la acción para el método GET y la URI dada
    }


    /**
     * Register a GET route.
     *
     * @param string $uri
     * @param Closure $action
     * @return void
     */
    public function get($uri, Closure $action) {
        $this->registerRoute(HttpMethod::GET, $uri, $action);
    }

    /**
     * Register a POST route.
     *
     * @param string $uri
     * @param Closure $action
     * @return void
     */
    public function post($uri, Closure $action) {
        $this->registerRoute(HttpMethod::POST, $uri, $action);
    }

    /**
     * Register a PUT route.
     *
     * @param string $uri
     * @param Closure $action
     * @return void
     */
    public function put($uri, Closure $action) {
        $this->registerRoute(HttpMethod::PUT, $uri, $action);
    }

    /**
     * Register a PATCH route.
     *
     * @param string $uri
     * @param Closure $action
     * @return void
     */
    public function patch($uri, Closure $action) {
        $this->registerRoute(HttpMethod::PATCH, $uri, $action);
    }

    /**
     * Register a DELETE route.
     *
     * @param string $uri
     * @param Closure $action
     * @return void
     */
    public function delete($uri, Closure $action) {
        $this->registerRoute(HttpMethod::DELETE, $uri, $action);
    }

}