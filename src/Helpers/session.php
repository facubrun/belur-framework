<?php

use Belur\Session\Session;

use function Belur\Helpers\app;

function session(): Session {
    return app()->session();
}

function error(string $field): ?string {
    $errors = session()->get('_errors', [])[$field] ?? [];

    $keys = array_keys($errors);

    if (count($keys) > 0) {
        return $errors[$keys[0]];
    }
    return null;
}

function old(string $field) {
    return session()->get('_old', [])[$field] ?? null;
}
