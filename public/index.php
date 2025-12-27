<?php

require_once  '../vendor/autoload.php';

use Belur\Http\HttpNotFoundException;
use Belur\Http\Request;
use Belur\Http\Response;
use Belur\Server\PhpNativeServer;
use Belur\Routing\Router;

$router = new Router();

$router->get('/test', function() {
    return Response::text('GET OK.');
});

$router->post('/test', function(Request $request) {
    return Response::text('POST OK.');
});

$router->get('/redirect', function(Request $request) {
    return Response::redirect('/test');
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
    $response = Response::text('404 Not Found')->setStatus(404);
    $server->sendResponse($response);
}