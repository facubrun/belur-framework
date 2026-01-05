<?php

namespace Belur\Validation\Rules;

class Number implements ValidationRule {

    public function message(): string {
        return "The field must be a valid number.";
    }

    public function isValid(string $field, array $data): bool {
        $number = trim($data[$field]);

        return is_numeric($number);
    }
}
