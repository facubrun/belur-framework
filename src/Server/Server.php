<?php

namespace Belur\Server;

use Belur\Http\HttpMethod;
use Belur\Http\Response;

/**
 * Similar to PHP ´$_SERVER´ but having an interface to be able to mock it, useful for testing.
 */
interface Server {
    /**
     * Get the request URI.
     *
     * @return string
     */
    public function requestUri(): string;

    /**
     * Get the request HTTP method.
     *
     * @return HttpMethod
     */
    public function requestMethod(): HttpMethod;

    /**
     * Get the POST data.
     *
     * @return array
     */
    public function postData(): array;

    /**
     * Get the query parameters.
     *
     * @return array
     */
    public function queryParams(): array;

    /**
     * Send the response to the client.
     *
     * @param Response $response
     * @return void
     */
    public function sendResponse(Response $response);
}