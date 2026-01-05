<?php

namespace Belur\Validation;

use Belur\Validation\Rules\Number;
use Belur\Validation\Rules\Email;
use Belur\Validation\Rules\LessThan;
use Belur\Validation\Rules\Required;
use Belur\Validation\Rules\RequiredWhen;
use Belur\Validation\Rules\RequiredWith;
use Belur\Validation\Rules\ValidationRule;

class Rule {
    public static function email(): ValidationRule {
        return new Email();
    }

    public static function required(): ValidationRule {
        return new Required();
    }

    public static function requiredWith(string $withField): ValidationRule {
        return new RequiredWith($withField);
    }

    public static function requiredWhen(string $otherField, string $operator, $value): ValidationRule {
        return new RequiredWhen($otherField, $operator, $value);
    }

    public static function number(): ValidationRule {
        return new Number();
    }

    public static function lessThan(int|float $value): ValidationRule {
        return new LessThan($value);
    }
}
