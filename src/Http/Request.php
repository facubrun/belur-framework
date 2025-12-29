<?php

namespace Belur\Http;

use Belur\Routing\Route;

/**
 * HTTP Request received from the client.
 */
class Request {
    /**
     * Request URI.
     *
     * @var string
     */
    protected string $uri;

    /**
     * Matched route by URI.
     *
     * @var Route
     */
    protected Route $route;

    /**
     * Request HTTP method.
     *
     * @var HttpMethod
     */
    protected HttpMethod $method;

    /**
     * Request data.
     *
     * @var array
     */
    protected array $data;

    /**
     * Query parameters.
     *
     * @var array
     */
    protected array $query;

    /**
     * Get request URI.
     *
     * @return string
     */
    public function uri(): string {
        return $this->uri;
    }

    /**
     * Set request URI.
     *
     * @param string $uri
     * @return self
     */
    public function setUri(string $uri): self {
        $this->uri = $uri;
        return $this;
    }

    /**
     * Get route for this request.
     *
     * @return Route
     */
    public function route(): Route {
        return $this->route;
    }

    /**
     * Set route for this request.
     *
     * @param Route $route
     * @return self
     */
    public function setRoute(Route $route): self {
        $this->route = $route;
        return $this;
    }

    /**
     * Get request HTTP method.
     *
     * @return HttpMethod
     */
    public function method(): HttpMethod {
        return $this->method;
    }

    /**
     * Set request HTTP method.
     *
     * @param HttpMethod $method
     * @return self
     */
    public function setMethod(HttpMethod $method): self {
        $this->method = $method;
        return $this;
    }

    /**
     * Get request POST data.
     *
     * @return array
     */
    public function data(): array {
        return $this->data;
    }

    /**
     * Set request POST data.
     *
     * @param array $data
     * @return self
     */
    public function setData(array $data): self {
        $this->data = $data;
        return $this;
    }

    /**
     * Get query parameters.
     *
     * @return array
     */
    public function query(): array {
        return $this->query;
    }

    /**
     * Set query parameters.
     *
     * @param array $query
     * @return self
     */
    public function setQueryParams(array $query): self {
        $this->query = $query;
        return $this;
    }

    /**
     * Get route parameters extracted from the URI.
     *
     * @return array
     */
    public function routeParams(?string $key = null): array {
        return $this->route()->parseParameters($this->uri());
    }
}
