<?php

namespace Belur\Tests;

use Belur\HttpMethod;
use Belur\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase {

    public function test_resolve_basic_route_with_callback_action() {
        $uri = '/test';
        $action = fn () => 'test';
        $router = new Router();
        $router->get($uri, $action);


        $route = $router->resolve($uri, HttpMethod::GET->value);
        $this->assertEquals($action, $route->action());
        $this->assertEquals($uri, $route->uri());
    }

    public function test_resolve_multiple_basic_routes_with_callback_action() {
        $routes = [
            '/test' => fn () => 'test',
            '/foo' => fn () => 'foo',
            '/bar' => fn () => 'bar',
            '/long/nested/route' => fn () => 'long nested route',
        ];
        $router = new Router();
        foreach ($routes as $uri => $action) {
            $router->get($uri, $action);
        }

        foreach ($routes as $uri => $action) {
            $route = $router->resolve($uri, HttpMethod::GET->value);
            $this->assertEquals($action, $route->action());
            $this->assertEquals($uri, $route->uri());
        }
    }

    public function test_resolve_multiple_basic_routes_with_callback_action_for_different_http_methods() {
        $routes = [
            [HttpMethod::GET->value, '/test', fn () => 'get test'],
            [HttpMethod::POST->value, '/test', fn () => 'post test'],
            [HttpMethod::PUT->value, '/test', fn () => 'put test'],
            [HttpMethod::PATCH->value, '/test', fn () => 'patch test'],
            [HttpMethod::DELETE->value, '/test', fn () => 'delete test'],

            [HttpMethod::GET->value, '/random/get', fn () => 'get test'],
            [HttpMethod::POST->value, '/random/nested/post', fn () => 'post test'],
            [HttpMethod::PUT->value, '/put/random/route', fn () => 'put test'],
            [HttpMethod::PATCH->value, '/some/patch/route', fn () => 'patch test'],
            [HttpMethod::DELETE->value, '/d', fn () => 'delete test'],
        ];

        $router = new Router();

        foreach ($routes as [$method, $uri, $action]) {
            $router->{strtolower($method)}($uri, $action);
        }

        foreach ($routes as [$method, $uri, $action]) {
            $route = $router->resolve($uri, $method);
            $this->assertEquals($action, $route->action());
            $this->assertEquals($uri, $route->uri());
        }
    }
}