<?php

require_once  '../vendor/autoload.php';

use Belur\Http\HttpNotFoundException;
use Belur\Http\Request;
use Belur\Http\Response;
use Belur\Server\PhpNativeServer;
use Belur\Routing\Router;

$router = new Router();

$router->get('/test/{param}', function(Request $request) {
    return Response::json($request->routeParams());
});

$router->post('/test', function(Request $request) {
    return Response::json($request->query());
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
    $request = $server->getRequest();
    $route = $router->resolve($request);
    $request->setRoute($route);
    $action = $route->action();
    $response = $action($request);
    $server->sendResponse($response);
} catch (HttpNotFoundException $e) {
    $response = Response::text('404 Not Found')->setStatus(404);
    $server->sendResponse($response);
}