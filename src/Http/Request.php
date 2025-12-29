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
    protected array $data = [];

    /**
     * Query parameters.
     *
     * @var array
     */
    protected array $query = [];

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
     * @param string|null $key Optional key to get specific value
     * @return mixed Returns specific value if key provided, otherwise returns all data
     */
    public function data(?string $key = null): mixed {
        if ($key === null) {
            return $this->data;
        }
        return $this->data[$key] ?? null;
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
     * @param string|null $key Optional key to get specific value
     * @return mixed Returns specific value if key provided, otherwise returns all query params
     */
    public function query(?string $key = null): mixed {
        if ($key === null) {
            return $this->query;
        }
        return $this->query[$key] ?? null;
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
     * @param string|null $key Optional key to get specific value
     * @return mixed Returns specific value if key provided, otherwise returns all route params
     */
    public function routeParams(?string $key = null): mixed {
        $params = $this->route()->parseParameters($this->uri());
        if ($key === null) {
            return $params;
        }
        return $params[$key] ?? null;
    }
}
