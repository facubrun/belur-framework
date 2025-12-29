<?php

namespace Lune\Tests\Http;

use Belur\Http\HttpMethod;
use Belur\Http\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase {

    public function test_request_returns_data_obtained_from_server() {
        $uri = '/test/uri';
        $method = HttpMethod::POST;
        $postData = ['product' => 'test product', 'price' => 100];
        $queryParams = ['page' => 2, 'sort' => 'asc'];

        $request = (new Request())
            ->setUri($uri)
            ->setMethod($method)
            ->setData($postData)
            ->setQueryParams($queryParams);

        $this->assertEquals($uri, $request->uri());
        $this->assertEquals($method, $request->method());
        $this->assertEquals($postData, $request->data());
        $this->assertEquals($queryParams, $request->query());
    }

}
