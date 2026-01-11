<?php

use App\Controllers\ContactController;
use App\Controllers\HomeController;
use App\Models\User;
use Belur\Auth\Auth;
use Belur\Http\Response;
use Belur\Routing\Route;


Route::get('/', fn () => redirect('/home'));

Route::get('/home', [HomeController::class, 'show']);

Route::get('/contacts', [ContactController::class, 'index']);
Route::get('/contacts/create', [ContactController::class, 'create']);
Route::post('/contacts', [ContactController::class, 'store']);

Auth::routes();