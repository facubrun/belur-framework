<?php

namespace Belur\Providers;

use Belur\View\BelurEngine;
use Belur\View\View;

use function Belur\Helpers\config;
use function Belur\Helpers\singleton;

class ViewServiceProvider implements ServiceProvider {
    public function registerServices(): void {
        match(config("view.engine", "belur")) {
            'belur' => singleton(View::class, fn () => new BelurEngine(config('view.path')))
        };
    }
}
