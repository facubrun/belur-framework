<?php

use function Belur\Helpers\env;

return [
    'name' => env('APP_NAME', 'Belur'),
    'env' => env('APP_ENV', 'dev'),
    'url' => env('APP_URL', 'localhost:8080'),
];