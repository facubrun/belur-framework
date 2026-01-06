<?php

namespace Belur\Tests\Validation;

use Belur\Validation\Exceptions\RuleParserException;
use Belur\Validation\Exceptions\UnknownRuleException;
use Belur\Validation\Rule;
use Belur\Validation\Rules\Email;
use Belur\Validation\Rules\LessThan;
use Belur\Validation\Rules\Number;
use Belur\Validation\Rules\Required;
use Belur\Validation\Rules\RequiredWhen;
use Belur\Validation\Rules\RequiredWith;
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

    public function test_parsing_unknown_rules_throws_unknown_rule_exception() {
        $this->expectException(UnknownRuleException::class);
        Rule::from('unknown_rule_test');
    }

    public static function rulesWithParameters() {
        return [
            [new LessThan(5), 'less_than:5'],
            [new RequiredWith('other'), 'required_with:other'],
            [new RequiredWhen('other', '==', 5), 'required_when:other,==,5'],
        ];
    }

    /**
     * @dataProvider rulesWithParameters
     */
    public function test_parse_rules_with_parameters($expected, $rule) {
        $this->assertEquals($expected, Rule::from($rule));
    }

    public static function rulesWithParametersWithError() {
        return [
            ["less_than"],
            ["less_than:1,2,3"],
            ["required_with:"],
            ["required_when:test,==,5,extra"],
            ["required_when:"],
            ["required_when:other,"],
            ["required_when:other,==,"],
        ];
    }

    /**
     * @dataProvider rulesWithParametersWithError
     */
    public function test_parsing_rule_with_parameters_without_passing_correct_parameters_throws_rule_parse_exception($rule) {
        $this->expectException(RuleParserException::class);
        Rule::from($rule);

    }
}
