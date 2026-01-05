<?php

namespace Belur\Validation;

use Belur\Validation\Exceptions\RuleParserException;
use Belur\Validation\Exceptions\UnknownRuleException;
use Belur\Validation\Rules\Number;
use Belur\Validation\Rules\Email;
use Belur\Validation\Rules\LessThan;
use Belur\Validation\Rules\Required;
use Belur\Validation\Rules\RequiredWhen;
use Belur\Validation\Rules\RequiredWith;
use Belur\Validation\Rules\ValidationRule;
use ReflectionClass;

class Rule {
    private static array $rules = [];

    private static array $defaultRules = [
        Required::class,
        RequiredWith::class,
        RequiredWhen::class,
        Email::class,
        Number::class,
        LessThan::class
    ];

    public static function loadDefaultRules() {
        self::load(self::$defaultRules);
    }

    public static function load(array $rules) {
        foreach ($rules as $class) {
            $className = array_slice(explode('\\', $class), -1)[0]; // ultimo elemento del namespace
            $ruleName = snake_case($className);
            self::$rules[$ruleName] = $class;
        }
    }

    public static function nameOf(ValidationRule $rule): string {
        $class = new ReflectionClass($rule);
        return snake_case($class->getShortName());

    }

    public static function parseBasicRule(string $ruleName): ValidationRule {
        $class = new ReflectionClass(self::$rules[$ruleName]); // inspecciona si el constructor no tiene parametros
        if (count($class->getConstructor()?->getParameters() ?? []) > 0) {
            throw new RuleParserException("Rule {$ruleName} requires parameters, but none has been passed.");
        }

        return $class->newInstance();
    }

    public static function parseRuleWithParameters(string $ruleName, string $params): ValidationRule {
        $class = new ReflectionClass(self::$rules[$ruleName]); // inspecciona si el constructor no tiene parametros
        $constructorParams = $class->getConstructor()?->getParameters() ?? [];
        $givenParams = array_filter(explode(',', $params), fn ($p) => !empty($p));

        if (count($givenParams) != count($constructorParams)) {
            throw new RuleParserException(sprintf(
                "Rule %s requires %d parameters, but %d were given: %s",
                $ruleName,
                count($constructorParams),
                count($givenParams),
                $params
            ));
        }

        return $class->newInstanceArgs($givenParams);
    }

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

    public static function from(string $str): ValidationRule {
        if (strlen($str) == 0) {
            throw new RuleParserException('Cant parse empty string to rule.');
        }

        $ruleParts = explode(':', $str);
        
        if (!array_key_exists($ruleParts[0], self::$rules)) {
            throw new UnknownRuleException("Rule {$ruleParts[0]} not found.");
        }

        if (count($ruleParts) == 1) {
            return self::parseBasicRule($ruleParts[0]);
        }

        [$ruleName, $params] = $ruleParts;

        return self::parseRuleWithParameters($ruleName, $params);
    }
}
