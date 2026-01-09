<?php

use Belur\App;

return [
    'boot' => [
        \Belur\Providers\ServerServiceProvider::class,
        \Belur\Providers\DatabaseDriverServiceProvider::class,
        \Belur\Providers\SessionStorageServiceProvider::class,
        \Belur\Providers\ViewServiceProvider::class,
        \Belur\Providers\AuthenticatorServiceProvider::class,
    ],
    'runtime' => [
        \App\Providers\RuleServiceProvider::class,
        \App\Providers\RouteServiceProvider::class,
    ]
];