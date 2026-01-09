<?php

return [
    'boot' => [
        \Belur\Providers\ServerServiceProvider::class,
        \Belur\Providers\DatabaseDriverServiceProvider::class,
        \Belur\Providers\SessionStorageServiceProvider::class,
        \Belur\Providers\ViewServiceProvider::class,
    ],
    'runtime' => [
        
    ]
];