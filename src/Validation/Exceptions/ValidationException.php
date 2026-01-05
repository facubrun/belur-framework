<?php

namespace Belur\Validation\Exceptions;

use Belur\Exceptions\BelurException;
use Throwable;

class ValidationException extends BelurException {
    public function __construct(protected array $errors) {
        $this->errors = $errors;
    }

    public function errors(): array {
        return $this->errors;
    }
}
