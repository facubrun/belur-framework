<?php

namespace Belur\Routing;

use Closure;

/**
 * Manipulation of routes
 */
class Route {
    protected string $uri;
    protected Closure $action;
    protected string $regex;
    protected array $parameters;

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
}
