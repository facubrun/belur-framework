<?php

namespace Belur\Validation;

use Belur\Validation\Rules\Email;
use Belur\Validation\Rules\ValidationRule;

class Rule {
    public static function email(): ValidationRule {
        return new Email();
    }
}