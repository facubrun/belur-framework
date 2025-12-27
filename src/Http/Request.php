<?php

namespace Belur\Http;

use Belur\Server\Server;

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
     * Request constructor.
     *
     * @param Server $server
     */
    public function __construct(Server $server) {
        $this->uri = $server->requestUri();
        $this->method = $server->requestMethod();
        $this->data = $server->postData();
        $this->query = $server->queryParams();
    }

    /**
     * Get request URI.
     *
     * @return string
     */
    public function uri(): string {
        return $this->uri;
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
     * Get request POST data.
     *
     * @return array
     */
    public function data(): array {
        return $this->data;
    }

    /**
     * Get query parameters.
     *
     * @return array
     */
    public function query(): array {
        return $this->query;
    }
}
