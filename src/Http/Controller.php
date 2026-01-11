<?php

namespace Belur\Http;

class Controller {
    /**
     * HTTP middlewares.
     *
     * @var Belur\Http\Middleware[]
     */
    protected array $middlewares = [];

    /**
     * Get all middlewares for the route.
     *
     * @return Belur\Http\Middleware[]
     */
    public function middlewares(): array {
        return $this->middlewares;
    }

    /**
     * Get all middlewares for the route (alias for middlewares).
     *
     * @return Belur\Http\Middleware[]
     */
    public function getMiddlewares(): array {
        return $this->middlewares();
    }

    /**
     * Set a middleware for the route.
     * @param array $middlewares Array of middleware class names or instances
     * @return self
     */
    public function setMiddlewares(array $middlewares): self {
        $this->middlewares = array_map(
            fn ($middleware) => is_string($middleware) ? new $middleware() : $middleware,
            $middlewares
        );
        return $this;
    }
}
