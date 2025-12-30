<?php

namespace Belur\Validation\Rules;

class RequiredWith implements ValidationRule {
    public function message(): string {
        return 'The field is required when the other specified fields are present.';
    }
    public function isValid(string $field, string $data): bool {
        return !empty($data);
    }
}