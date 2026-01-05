<?php

namespace Belur\Validation;

use Belur\Validation\Rules\Email;
use Belur\Validation\Rules\Required;
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
}
