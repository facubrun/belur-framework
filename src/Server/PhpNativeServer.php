<?php

namespace Belur\Server;

use Belur\Http\HttpMethod;
use Belur\Http\Request;
use Belur\Http\Response;

/**
 * PHP Native Server implementation.
 */
class PhpNativeServer implements Server {
    /**
     * @inheritDoc
     */
    public function getRequest(): Request {
        return new Request()
        ->setUri(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))
        ->setMethod(HttpMethod::from($_SERVER['REQUEST_METHOD']))
        ->setData($_POST)
        ->setQueryParams($_GET);

    }

    /**
     * @inheritDoc
     */
    public function sendResponse(Response $response) {
        // PHP manda content-type por defecto si
        // no se setea uno, asi que lo removemos manualmente
        header("Content-Type: None");
        header_remove("Content-Type");

        $response->prepare();
        http_response_code($response->status());
        foreach ($response->headers() as $header => $value) {
            header("$header: $value");
        }
        print($response->body());
    }
}
