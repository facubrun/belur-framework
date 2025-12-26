<?php

namespace Belur\Server;

use Belur\Http\HttpMethod;
use Belur\Http\Response;

interface Server {
    public function requestUri(): string;

    public function requestMethod(): HttpMethod;

    public function postData(): array;

    public function queryParams(): array;

    public function sendResponse(Response $response);
}