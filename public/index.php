<?php

require_once  '../vendor/autoload.php';

use Belur\HttpNotFoundException;
use Belur\Router;

$router = new Router();

$router->get('/test', function() {
    return 'GET OK.';
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


try {
    $action = $router->resolve($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
    print($action());
} catch (HttpNotFoundException $e) {
    print("Not Found");
    http_response_code(404);
}