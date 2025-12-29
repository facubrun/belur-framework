<?php

namespace Lune\Tests\Http;

use Belur\Http\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase {

    public function test_json_response_is_constructed_correctly() {
        $data = ['product' => 'test product', 'price' => 100];
        $response = Response::json($data);

        $this->assertEquals(200, $response->status());
        $this->assertEquals(['content-type' => 'application/json'], $response->headers());
        $this->assertEquals(json_encode($data), $response->body());
    }

    public function test_text_response_is_constructed_correctly() {
        $response = Response::text('test text response');

        $this->assertEquals(200, $response->status());
        $this->assertEquals(['content-type' => 'text/plain'], $response->headers());
        $this->assertEquals('test text response', $response->body());
    }

    public function test_redirect_response_is_constructed_correctly() {
        $response = Response::redirect('/redirect/path');

        $this->assertEquals(302, $response->status());
        $this->assertEquals(['location' => '/redirect/path'], $response->headers());
        $this->assertEquals('', $response->body());
    }

    public function test_prepare_method_removes_content_headers_if_there_is_no_content() {
        $response = new Response();
        $response->setContentType('text/plain')
                ->setBody(null)
                ->prepare();
        
        $this->assertArrayNotHasKey('content-type', $response->headers());
        $this->assertArrayNotHasKey('content-length', $response->headers());
    }

    public function test_prepare_method_adds_content_length_header_if_there_is_content() {
        $response = new Response();
        $response->setBody('test')
                ->prepare();
        
        $this->assertEquals('4', $response->headers()['content-length']);
    }
}
