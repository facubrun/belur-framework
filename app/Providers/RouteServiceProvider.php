<?php

namespace App\Providers;

use Belur\App;
use Belur\Providers\ServiceProvider;
use Belur\Routing\Route;

class RouteServiceProvider implements ServiceProvider{
    public function registerServices(): void {
        Route::load(App::$root . '/routes');
    }
}