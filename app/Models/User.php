<?php

namespace App\Models;

use Belur\Auth\Authenticatable;

class User extends Authenticatable {
    protected array $hidden = [];

    protected array $fillable = [
        'name',
        'email',
        'password',
    ];
}