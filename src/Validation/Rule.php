<?php

namespace Belur\Validation;

use Belur\Validation\Rules\Email;
use Belur\Validation\Rules\Required;
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
}
