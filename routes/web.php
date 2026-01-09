<?php

use App\Controllers\Auth\RegisterController;
use App\Models\User;
use Belur\Crypto\Hasher;
use Belur\Http\Response;
use Belur\Routing\Route;

use function Belur\Helpers\app;

Route::get('/', function ($request) {
    if (isGuest()) {
        return Response::text('Guest');
    }

    return Response::text(auth()->name);
});

Route::get('/form', fn ($request) => view('form', []));

Route::get('/register', [RegisterController::class, 'create']);

Route::post('/register', [RegisterController::class, 'store']);

Route::get('/login', fn ($request) => view('auth/login', []));

Route::post('/login', function ($request) {
    $data = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    $user = User::firstWhere('email', $data['email']);

    if (is_null($user) || !app(Hasher::class)->verify($data['password'], $user->password)) {
        return back()->withErrors(['email' => ['email' => 'Email not found']]);
    }

    $user->login();
    return redirect('/');
});

Route::get('/logout', function ($request) {
    auth()->logout();
    return redirect('/');
});