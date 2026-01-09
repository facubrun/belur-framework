<?php

namespace Belur\Helpers;

use Belur\App;
use Belur\Container\Container;
use Belur\Config\Config;

function app($class = App::class): mixed {
    return Container::resolve($class);
}

function singleton(string $class, string|callable|null $build = null): mixed {
    return Container::singleton($class, $build);
}

function env(string $variable, $default = null) {
    return $_ENV[$variable] ?? $default;
}

function config(string $configuration, $default = null): mixed {
    return Config::get($configuration, $default);
}

function resourcesDirectory(): string {
    return App::$root . '/resources';
}
