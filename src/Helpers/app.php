<?php

namespace Belur\Helpers;

use Belur\App;
use Belur\Container\Container;

function app($class = App::class): mixed {
    return Container::resolve($class);
}

function singleton(string $class): mixed {
    return Container::singleton($class);
}
