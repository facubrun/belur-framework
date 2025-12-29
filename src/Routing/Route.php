<?php

namespace Belur\Routing;

use Belur\App;
use Belur\Container\Container;
use Belur\Http\Middleware;
use Closure;

/**
 * Manipulation of routes
 */
class Route {
    protected string $uri;
    
    /**
     * The action to be executed for the route.
     *
     * @var Closure
     */
    protected Closure $action;
    
    /**
     * Regex pattern for matching the route.
     *
     * @var string
     */
    protected string $regex;

    /**
     * Route parameters.
     *
     * @var array
     */
    protected array $parameters;

    /**
     * HTTP middlewares.
     *
     * @var Belur\Http\Middleware[]
     */
    protected array $middlewares = [];

    public function __construct(string $uri, Closure $action) {
        $this->uri = $uri;
        $this->action = $action;
        $this->regex = preg_replace('/\{([a-zA-Z]+)\}/', '([a-zA-Z0-9_]+)', $uri);

        preg_match_all('/\{([a-zA-Z]+)\}/', $uri, $parameters);
        $this->parameters = $parameters[1];
    }

    /**
     * Get the URI of the route.
     *
     * @return string
     */
    public function uri(): string {
        return $this->uri;
    }

    /**
     * Get the action of the route.
     *
     * @return Closure
     */
    public function action(): Closure {
        return $this->action;
    }

    /**
     * Get all middlewares for the route.
     *
     * @return Belur\Http\Middleware[]
     */
    public function middlewares(): array {
        return $this->middlewares;
    }

    /**
     * Set a middleware for the route.
     * @param Belur\Http\Middleware $middleware
     * @return self
     */
    public function setMiddlewares(array $middlewares): self {
        $this->middlewares = array_map(fn ($middleware) => new $middleware(), $middlewares);
        return $this;
    }

    /**
     * Check if the route has middlewares.
     *
     * @return boolean
     */
    public function hasMiddlewares(): bool {
        return !empty($this->middlewares);
    }

    /**
     * Get the matches of a regex from the URI.
     *
     * @param string $uri
     * @return boolean
     */
    public function matches(string $uri): bool {
        return preg_match("#^$this->regex/?$#", $uri);
    }

    /**
     * Check if the route has parameters.
     *
     * @return boolean
     */
    public function hasParameters(): bool {
        return count($this->parameters) > 0;
    }

    /**
     * Parse parameters from the URI.
     *
     * @param string $uri
     * @return array
     */
    public function parseParameters(string $uri): array {
        preg_match("#^$this->regex$#", $uri, $arguments);

        return array_combine($this->parameters, array_slice($arguments, 1)); // elimina el primer elemento que es la cadena completa
    }

    public static function get(string $uri, Closure $action): Route {
        return Container::resolve(App::class)->router->get($uri, $action);
    }
}
