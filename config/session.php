<?php

use function Belur\Helpers\env;

return [
    'storage' => env('SESSION_STORAGE', 'native'),
];

