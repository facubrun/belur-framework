<?php

use Belur\Http\Response;
use Belur\Routing\Route;

Route::get('/', fn ($request) => Response::text('Belur Framework'));

Route::get('/form', fn ($request) => view('form', []));