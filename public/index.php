<?php

require_once  '../vendor/autoload.php';

use Belur\App;
use Belur\Container\Container;
use Belur\Http\Request;
use Belur\Http\Response;
use Belur\Routing\Router;

Container::singleton(Router::class);

$app = new App();

$app->router->get('/test/{param}', function(Request $request) {
    return Response::json($request->routeParams('param'));
});

$app->router->post('/test', function(Request $request) {
    return Response::json($request->query('test'));
});

$app->router->get('/redirect', function(Request $request) {
    return Response::redirect('/test');
});

$app->router->put('/test', function() {
    return 'PUT OK.';
});

$app->router->patch('/test', function() {
    return 'PATCH OK.';
});

$app->router->delete('/test', function() {
    return 'DELETE OK.';
});


$app->run();