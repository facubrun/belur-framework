<?php

namespace Belur\Validation;

use Belur\Validation\Exceptions\RuleParserException;
use Belur\Validation\Rules\Number;
use Belur\Validation\Rules\Email;
use Belur\Validation\Rules\LessThan;
use Belur\Validation\Rules\Required;
use Belur\Validation\Rules\RequiredWhen;
use Belur\Validation\Rules\RequiredWith;
use PHPUnit\Framework\TestCase;

class ValidationRuleTest extends TestCase {
    public static function emails() {
        return [
            ["test@test.com", true],
            ["antonio@mastermind.ac", true],
            ["test@testcom", false],
            ["test@test.", false],
            ["antonio@", false],
            ["antonio@.", false],
            ["antonio", false],
            ["@", false],
            ["", false],
            [null, false],
            [4, false],
        ];
    }


    public static function requiredData() {
        return [
            ["", false],
            [null, false],
            [5, true],
            ["test", true],
        ];
    }

    public static function numbers() {
        return [
            [0, true],
            [1, true],
            [1.5, true],
            [-1, true],
            [-1.5, true],
            ["0", true],
            ["1", true],
            ["1.5", true],
            ["-1", true],
            ["-1.5", true],
            ["test", false],
            ["1test", false],
            ["-5test", false],
            ["", false],
            [null, false],
        ];
    }

    public static function lessThanData() {
        return [
            [5, 5, false],
            [5, 6, false],
            [5, 3, true],
            [5, null, false],
            [5, "", false],
            [5, "test", false],
        ];
    }

    public static function requiredWhenData() {
        return [
            ["other", "==", "value", ["other" => "value"], "test", false],
            ["other", "==", "value", ["other" => "value", "test" => 1], "test", true],
            ["other", "==", "value", ["other" => "not value"], "test", true],
            ["other", ">", 5, ["other" => 1], "test", true],
            ["other", ">", 5, ["other" => 6], "test", false],
            ["other", ">", 5, ["other" => 6, "test" => 1], "test", true],
        ];
    }

    /**
     * @dataProvider emails
     */
    public function test_email($email, $expected) {
        $data = ['email' => $email];
        $rule = new Email();
        $this->assertEquals($expected, $rule->isValid('email', $data));
    }

    /**
     * @dataProvider requiredData
    */
    public function test_required($value, $expected) {
        $data = ['test' => $value];
        $rule = new Required();
        $this->assertEquals($expected, $rule->isValid('test', $data));
    }

    public function test_required_with() {
        $rule = new RequiredWith('other');
        $data = ['test' => 5, 'other' => 'value2']; // other is present
        $this->assertTrue($rule->isValid('test', $data));

        $data = ['other' => 'value2'];
        $this->assertFalse($rule->isValid('test', $data));
    }

    /**
     * @dataProvider numbers
    */
    public function test_number($number, $expected) {
        $rule = new Number();
        $data = ['num' => $number];
        $this->assertEquals($expected, $rule->isValid('num', $data));

    }

    /**
     * @dataProvider lessThanData
    */
    public function test_less_than($value, $check, $expected) {
        $rule = new LessThan($value);
        $data = ['test' => $check];
        $this->assertEquals($expected, $rule->isValid('test', $data));
    }

    /**
     * @dataProvider requiredWhenData
    */
    public function test_required_when($otherField, $operator, $value, $data, $field, $expected) {
        $rule = new RequiredWhen($otherField, $operator, $value);
        $this->assertEquals($expected, $rule->isValid($field, $data));
    }

    public function test_required_when_throws_parse_rule_exception_when_operator_is_invalid() {
        $this->expectException(RuleParserException::class);
        $rule = new RequiredWhen('other', 'xd', 'test');
        $data = ['other' => 'test example', 'test' => 1];
        $rule->isValid('test', $data);
    }
}
