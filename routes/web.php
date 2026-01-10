<?php

use App\Controllers\Auth\LoginController;
use App\Controllers\Auth\RegisterController;
use App\Models\User;
use Belur\Auth\Auth;
use Belur\Crypto\Hasher;
use Belur\Http\Response;
use Belur\Routing\Route;

use function Belur\Helpers\app;

Route::get('/', function () {
    if (isGuest()) {
        return Response::text('Guest');
    }

    return Response::text(auth()->name);
});

Route::get('/form', fn () => view('form', []));

Route::get('/user/{user}', function (User $user) {
    return Response::json($user->toArray());
});

Auth::routes();