<?php

use function Belur\Helpers\env;

return [
    'driver' => env('FILE_STORAGE', 'disk'),
];