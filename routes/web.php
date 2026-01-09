<?php

use App\Models\User;
use Belur\Crypto\Hasher;
use Belur\Http\Response;
use Belur\Routing\Route;

use function Belur\Helpers\app;

Route::get('/', fn ($request) => Response::text('Belur Framework'));

Route::get('/form', fn ($request) => view('form', []));

Route::get('/register', fn ($request) => view('auth/register', []));
Route::post('/register', function ($request) {
    $data = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
        'confirm_password' => ['required'],
        'name' => ['required']
    ]);

    if($data['password'] != $data['confirm_password']) {
        return back()->withErrors(
            ['confirm_password' => ['confirm_password' => 'Passwords do not match']]);
    }
    $data['password'] = app(Hasher::class)->hash($data['password']);

    User::create($data);

    $user = User::firstWhere('email', $data['email']);
    $user->login();
    return redirect('/');
});