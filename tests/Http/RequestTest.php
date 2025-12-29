<?php

namespace Lune\Tests\Http;

use Belur\Http\HttpMethod;
use Belur\Http\Request;
use Belur\Routing\Route;
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

    public function test_data_returns_value_if_key_is_given() {
        $method = HttpMethod::POST;
        $postData = ['product' => 'test product', 'price' => 100];

        $request = (new Request())
            ->setMethod($method)
            ->setData($postData);

        // Verifica que devuelve el valor correcto cuando la clave existe
        $this->assertEquals('test product', $request->data('product'));
        $this->assertEquals(100, $request->data('price'));
        
        // Verifica que devuelve null cuando la clave no existe
        $this->assertNull($request->data('key_not_exist'));
        
        // Verifica que devuelve el array completo cuando no se pasa parametro
        $this->assertEquals($postData, $request->data());
    }

    public function test_query_returns_value_if_key_is_given() {
        $queryParams = ['test' => 2, 'param2' => 'value'];

        $request = (new Request())
            ->setQueryParams($queryParams);

        // Verifica que devuelve el valor correcto cuando la clave existe
        $this->assertEquals(2, $request->query('test'));
        $this->assertEquals('value', $request->query('param2'));
        
        // Verifica que devuelve null cuando la clave no existe
        $this->assertNull($request->query('key_not_exist'));
        
        // Verifica que devuelve el array completo cuando no se pasa parámetro
        $this->assertEquals($queryParams, $request->query());
    }

    public function test_route_params_returns_value_if_key_is_given() {
        $uri = '/products/111/features/222';
        $route = new Route('/products/{productId}/features/{featureId}', fn () => 'test');

        $request = (new Request())
            ->setUri($uri)
            ->setRoute($route);

        // Verifica que devuelve el valor correcto cuando la clave existe
        $this->assertEquals('111', $request->routeParams('productId'));
        $this->assertEquals('222', $request->routeParams('featureId'));
        
        // Verifica que devuelve null cuando la clave no existe
        $this->assertNull($request->routeParams('key_not_exist'));
        
        // Verifica que devuelve el array completo cuando no se pasa parámetro
        $this->assertEquals(['productId' => '111', 'featureId' => '222'], $request->routeParams());
    }

}
