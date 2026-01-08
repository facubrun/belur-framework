<?php

require_once  '../vendor/autoload.php';

use Belur\App;
use Belur\Database\DB;
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

Route::post('/validate', function(Request $request) {
    $validated = $request->validate([
        'test' => 'required',
        'num' => 'number',
        'email' => ['required_when:num,>,2', 'email'],
    ],
    [
        'email' => [
            'email' => 'MENSAJE TEST.'
        ]
    ]);

    return json(['validated' => $validated]);
});

Route::get('/session', function (Request $request) {
    //session()->flash('alert', 'success');
    return json($_SESSION);
});

Route::get('/form', fn (Request $request) => view('form', []));

Route::post('/form', function (Request $request) {
    return json($request->validate([
        'email' => 'email',
        'name' => 'required'
    ]));
});

Route::post('/user', function(Request $request) {
    $results = DB::statement('INSERT INTO users (name,email) VALUES (?, ?)', [$request->data('name'), $request->data('email')]);
    return json(['message' => 'ok']);
});

Route::get('/users', function(Request $request) {
    return json(['users' => DB::statement('SELECT * FROM users')]);
});

class User extends Belur\Database\Model {
    protected array $fillable = ['name', 'email'];
}

Route::post('/user/model', function(Request $request) {
    return json(User::create($request->data())->toArray());
});

Route::get('/user/query', function(Request $request) {
    return json(User::first()->toArray());
});

Route::get('/user/all', function(Request $request) {
    $users = User::all();
    return json([
        'count' => count($users),
        'users' => array_map(fn($user) => $user?->toArray() ?? 'null', $users)
    ]);
});

Route::get('/user/where', function(Request $request) {
    $users = User::where('name', 'testaco');
    return json([
        'count' => count($users),
        'users' => array_map(fn($user) => $user?->toArray() ?? 'null', $users)
    ]);
});

Route::get('/user/{id}', function(Request $request) {
    return json(User::find($request->routeParams('id'))->toArray());
});

Route::post('/users/{id}/update', function(Request $request) {
    $user = User::find($request->routeParams('id'));

    $user->name = $request->data('name');
    $user->email = $request->data('email');

    return json($user->update()->toArray());
});

Route::delete('/users/{id}/delete', function(Request $request) {
    $user = User::find($request->routeParams('id'));

    return json($user->delete()->toArray());
});

$app->run();