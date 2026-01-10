<?php

use App\Models\User;
use Belur\Auth\Auth;
use Belur\Http\Response;
use Belur\Routing\Route;


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