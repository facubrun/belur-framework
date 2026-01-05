<?php

namespace Belur\Validation\Rules;

class RequiredWhen implements ValidationRule {

    public function __construct(private string $otherField, private string $operator, private $value) {
        $this->otherField = $otherField;
        $this->operator = $operator;
        $this->value = $value;
    }

    public function message(): string {
        return "This field is required when {$this->otherField} {$this->operator} {$this->value}.";
    }

    public function isValid(string $field, array $data): bool {
        if (!array_key_exists($this->otherField, $data)) {
            return false;
        }

        $isRequired = match ($this->operator) {
            '==' => $data[$this->otherField] == $this->value,
            '!=' => $data[$this->otherField] != $this->value,
            '>'  => $data[$this->otherField] > $this->value,
            '<'  => $data[$this->otherField] < $this->value,
            '>=' => $data[$this->otherField] >= $this->value,
            '<=' => $data[$this->otherField] <= $this->value,
        };
        
        if ($isRequired) {
            return isset($data[$field]) && !empty($data[$field]);
        }
        return true;
    }
}
