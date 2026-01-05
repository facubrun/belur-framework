<?php

namespace Belur\Validation\Rules;

class LessThan implements ValidationRule {
    private float $lessThan;

    public function __construct(float $lessThan) {
        $this->lessThan = $lessThan;
    }
            
    public function message(): string {
        return "The field must be less than a specified value.";
    }

    public function isValid(string $field, array $data): bool {
        return isset($data[$field])
        && is_numeric($data[$field])
        && $data[$field] < $this->lessThan;
    }
}
