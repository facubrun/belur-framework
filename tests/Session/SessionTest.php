<?php

namespace Belur\Tests\Session;

use Belur\Session\Session;
use Belur\Session\SessionStorage;
use PHPUnit\Framework\TestCase;

class SessionTest extends TestCase {
    private function mockSessionStorage() {
        $mock = $this->getMockBuilder(SessionStorage::class)->getMock();
        $mock->method("id")->willReturn("id");
        $mock->storage = [];
        $mock->method("get")->willReturnCallback(fn ($key) => $mock->storage[$key] ?? null);
        $mock->method("set")->willReturnCallback(fn ($key, $value) => $mock->storage[$key] = $value);
        $mock->method("has")->willReturnCallback(fn ($key) => isset($mock->storage[$key]));
        $mock->method("remove")->willReturnCallback(function ($key) use ($mock) {
            unset($mock->storage[$key]);
        });

        return $mock;
    }
    public function testAgeFlashData() {
        $mock = $this->mockSessionStorage();

        $data1 = new Session($mock);

        $data1->set('test', 1);

        $this->assertTrue(isset($mock->storage['test']));

        // chequeo que los datos flash se configuran correctamente
        $this->assertEquals(['old' => [], 'new' => []], $mock->storage[$data1::FLASH_KEY]);
        $data1->flash('alert', 'message');
        $this->assertEquals(['old' => [], 'new' => ['alert']], $mock->storage[$data1::FLASH_KEY]);

        // chequeo que los datos flash siguen configurados y las claves se envejecen
        $data1->__destruct();
        $this->assertTrue(isset($mock->storage['alert']));
        $this->assertEquals(['old' => ['alert'], 'new' => []], $mock->storage[$data1::FLASH_KEY]);

        // creo nueva sesion y chequeo datos anteriores
        $data2 = new Session($mock);
        $this->assertEquals(['old' => ['alert'], 'new' => []], $mock->storage[$data2::FLASH_KEY]);
        $this->assertTrue(isset($mock->storage['alert']));

        // Destroy session and check that flash keys are removed
        $data2->__destruct();
        $this->assertEquals(['old' => [], 'new' => []], $mock->storage[$data2::FLASH_KEY]);
        $this->assertFalse(isset($mock->storage['alert']));
    }
}
