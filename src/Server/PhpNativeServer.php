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
    protected function requestData(): array {
        $headers = array_change_key_case(getallheaders(), CASE_LOWER);
        $contentType = $headers['content-type'] ?? '';
        $isJson = str_starts_with($contentType, 'application/json');

        $data = [];

        // Standard form POST
        if ($_SERVER['REQUEST_METHOD'] === HttpMethod::POST->value && !$isJson) {
            return $_POST ?? [];
        }

        $raw = file_get_contents('php://input');

        if ($isJson) {
            $data = json_decode($raw, associative: true);
            if (!is_array($data) || $data === null) {
                parse_str($raw, $data);
            }
        } else {
            parse_str($raw, $data);
        }

        if (!is_array($data)) {
            $data = [];
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function getRequest(): Request {
        return (new Request())
        ->setUri(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))
        ->setMethod(HttpMethod::from($_SERVER['REQUEST_METHOD']))
        ->setHeaders(getallheaders())
        ->setData($this->requestData())
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
