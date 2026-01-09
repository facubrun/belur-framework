<?php

use Belur\Auth\Auth;
use Belur\Auth\Authenticatable;

function auth(): ?Authenticatable {
    return Auth::user();
}

function isGuest(): bool {
    return Auth::isGuest();
}
