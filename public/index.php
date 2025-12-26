<?php

require_once  '../vendor/autoload.php';

use Belur\Http\HttpNotFoundException;
use Belur\Http\Request;
use Belur\Http\Response;
use Belur\Server\PhpNativeServer;
use Belur\Routing\Router;

$router = new Router();

$router->get('/test', function() {
    $response = new Response();
    $response->setHeader('Content-Type', 'application/json');
    $response->setBody(json_encode(['message' => 'GET OK.']));
    return $response;
});

$router->post('/test', function() {
    return 'POST OK.';
});

$router->put('/test', function() {
    return 'PUT OK.';
});

$router->patch('/test', function() {
    return 'PATCH OK.';
});

$router->delete('/test', function() {
    return 'DELETE OK.';
});


$server = new PhpNativeServer();
try {
    $request = new Request($server);
    $route = $router->resolve($request);
    $action = $route->action();
    $response = $action($request);
    $server->sendResponse($response);
} catch (HttpNotFoundException $e) {
    $response = new Response();
    $response->setStatus(404);
    $response->setBody('404 Not Found');
    $server->sendResponse($response);
}