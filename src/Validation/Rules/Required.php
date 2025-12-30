<?php

namespace Belur\Validation\Rules;

class Required implements ValidationRule {
    public function message(): string {
        return "This field is required.";
    }

    public function isValid(string $field, string $data): bool {
        return isset($data[$field]) && $data[$field] !== '';
    }
}