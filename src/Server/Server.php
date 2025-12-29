<?php

namespace Belur\Server;

use Belur\Http\Request;
use Belur\Http\Response;

/**
 * Similar to PHP ´$_SERVER´ but having an interface to be able to mock it, useful for testing.
 */
interface Server {
    /**
     * Get the request sent by the client.
     *
     * @return Request
     */
    public function getRequest(): Request;

    /**
     * Send the response to the client.
     *
     * @param Response $response
     * @return void
     */
    public function sendResponse(Response $response);
}
