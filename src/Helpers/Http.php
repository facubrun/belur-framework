<?php

use Belur\Http\Request;
use Belur\Http\Response;

use function Belur\Helpers\app;

function json(array $data): Response {
    return Response::json($data);
}

function redirect(string $uri): Response {
    return Response::redirect($uri);
}

function view(string $view, array $params, ?string $layout = null): Response {
    return Response::view($view, $params, $layout);
}

function request(): Request {
    return app()->request;
}
