<?php

namespace Belur\Server;

use Belur\Http\HttpMethod;
use Belur\Http\Request;
use Belur\Http\Response;
use Belur\Storage\File;

/**
 * PHP Native Server implementation.
 */
class PhpNativeServer implements Server {
    /**
     * @inheritDoc
     */
    public function getRequest(): Request {
        return (new Request())
        ->setUri(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))
        ->setMethod(HttpMethod::from($_SERVER['REQUEST_METHOD']))
        ->setHeaders(getallheaders())
        ->setData($_POST)
        ->setQueryParams($_GET)
        ->setFiles($this->uploadedFiles());

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

    public function uploadedFiles(): array {
        $files = [];
        foreach ($_FILES as $key => $file) {
            if (!empty($file['tmp_name'])) {
                $files[$key] = new File(
                    file_get_contents($file['tmp_name']),
                    $file['type'],
                    $file['name'],
                );
            }
    }

    return $files;
    }
}
