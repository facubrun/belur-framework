<?php

require_once  '../vendor/autoload.php';

use Belur\App;
use Belur\Http\Middleware;
use Belur\Http\Request;
use Belur\Http\Response;
use Belur\Routing\Route;

$app = App::bootstrap();

$app->router->get('/test/{param}', function(Request $request) {
    return json($request->routeParams('param'));
});

$app->router->post('/test', function(Request $request) {
    return json($request->query('test'));
});

$app->router->get('/redirect', function(Request $request) {
    return redirect('/test');
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


class AuthMiddleware implements Middleware {
    public function handle(Request $request, Closure $next): Response {
        if ($request->headers('Authorization') !== 'test') {
            return json(['message' => 'Unauthorized'])->setStatus(401);
        }
        return $next($request); // llama al siguiente middleware
    }
}

Route::get('/middlewares', fn (Request $request) => json(['message' => 'Middleware works!']))
    ->setMiddlewares([AuthMiddleware::class]);

Route::get('/html', fn(Request $request) => view('home', ['user' => 'Test']));

$app->run();