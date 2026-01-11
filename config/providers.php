<?php

use Belur\App;

return [

    /**
     * Service providers that will run before booting application
     */
    'boot' => [
        \Belur\Providers\ServerServiceProvider::class,
        \Belur\Providers\DatabaseDriverServiceProvider::class,
        \Belur\Providers\SessionStorageServiceProvider::class,
        \Belur\Providers\ViewServiceProvider::class,
        \Belur\Providers\AuthenticatorServiceProvider::class,
        \Belur\Providers\HasherServiceProvider::class,
        \Belur\Providers\FileStorageServiceProvider::class,
    ],

    /**
     * Service providers that will run on application runtime
     */
    'runtime' => [
        \App\Providers\RuleServiceProvider::class,
        \App\Providers\RouteServiceProvider::class,
        \App\Providers\AppServiceProvider::class,
    ],

    /**
     * Service providers that will run only on CLI environment
     */
    'cli' => [
        \Belur\Providers\DatabaseDriverServiceProvider::class,
    ]
];