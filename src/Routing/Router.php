<?php

namespace Belur\Routing;

use Belur\Container\DependencyInjection;
use Belur\Http\HttpMethod;
use Belur\Http\HttpNotFoundException;
use Belur\Http\Request;
use Belur\Http\Response;
use Closure;

/**
 * HTTP Router
 */
class Router {
    protected array $routes = []; // protected para que se pueda extender la clase

    /**
     * Constructor initializes the routes array for each HTTP method.
     */
    public function __construct() {
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
    public function resolveRoute(Request $request): Route {
        foreach ($this->routes[$request->method()->value] as $route) {
            if ($route->matches($request->uri())) {
                return $route;
            }
        }
        throw new HttpNotFoundException();
    }

    /**
     * Resolve the route of the ´$request´ and return the response
     *
     * @param Request $request
     * @return Response
     */
    public function resolve(Request $request): Response {
        $route = $this->resolveRoute($request);
        $request->setRoute($route);
        $action = $route->action();

        if (is_array($action)) {
            $controller = new $action[0]();
            $action[0] = $controller;
        }

        $params = DependencyInjection::resolveParameters($action, $request->routeParams());

        return $this->runMiddlewares(
            $request,
            $route->middlewares(),
            fn () => call_user_func($action, ...$params)
        );
    }

    protected function runMiddlewares(Request $request, array $middlewares, Closure $target): Response {
        if (count($middlewares) === 0) {
            return $target($request);
        }
        
        return $middlewares[0]->handle(
            $request,
            fn ($request) => $this->runMiddlewares($request, array_slice($middlewares, 1), $target)
        );
    }

    /**
     * Register a route for the given HTTP method, URI and action.
     * @param HttpMethod $method
     * @param string $uri
     * @param Closure $action
     * @return Route
     */
    protected function registerRoute(HttpMethod $method, string $uri, Closure|array $action): Route {
        $route = new Route($uri, $action);
        return $this->routes[$method->value][] = $route; // almacena la acción para el método GET y la URI dada
    }


    /**
     * Register a GET route.
     *
     * @param string $uri
     * @param Closure $action
     * @return Route
     */
    public function get($uri, Closure|array $action): Route {
        return $this->registerRoute(HttpMethod::GET, $uri, $action);
    }

    /**
     * Register a POST route.
     *
     * @param string $uri
     * @param Closure $action
     * @return Route
     */
    public function post($uri, Closure|array $action): Route {
        return $this->registerRoute(HttpMethod::POST, $uri, $action);
    }

    /**
     * Register a PUT route.
     *
     * @param string $uri
     * @param Closure $action
     * @return Route
     */
    public function put($uri, Closure|array $action): Route {
        return $this->registerRoute(HttpMethod::PUT, $uri, $action);
    }

    /**
     * Register a PATCH route.
     *
     * @param string $uri
     * @param Closure $action
     * @return Route
     */
    public function patch($uri, Closure|array $action): Route {
        return $this->registerRoute(HttpMethod::PATCH, $uri, $action);
    }

    /**
     * Register a DELETE route.
     *
     * @param string $uri
     * @param Closure $action
     * @return Route
     */
    public function delete($uri, Closure|array $action): Route {
        return $this->registerRoute(HttpMethod::DELETE, $uri, $action);
    }
}
