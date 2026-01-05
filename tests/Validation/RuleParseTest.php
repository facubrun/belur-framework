<?php

namespace Belur\Tests\Validation;

use Belur\Validation\Rule;
use Belur\Validation\Rules\Email;
use Belur\Validation\Rules\Number;
use Belur\Validation\Rules\Required;
use PHPUnit\Framework\TestCase;

class RuleParseTest extends TestCase {
    protected function setUp(): void {
        Rule::loadDefaultRules();
    }

    public static function basic_rules() {
        return [
            [Email::class, 'email'],
            [Required::class, 'required'],
            [Number::class, 'number']
        ];
    }

    /**
     * @dataProvider basic_rules
     */
    public function test_parse_basic_rules($class, $name) {
        $this->assertInstanceOf($class, Rule::from($name));
    }
}
