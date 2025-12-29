<?php

namespace Belur\Tests\Routing;

use Belur\Http\HttpMethod;
use Belur\Http\Request;
use Belur\Http\Response;
use Belur\Routing\Router;
use Belur\Server\Server;
use Closure;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase {

    private function createMockRequest(string $uri, HttpMethod $method): Request {
        return (new Request())
            ->setUri($uri)
            ->setMethod($method);
    }

    public function test_resolve_basic_route_with_callback_action() {
        $uri = '/test';
        $action = fn () => 'test';
        $router = new Router();
        $router->get($uri, $action);


        $route = $router->resolveRoute($this->createMockRequest($uri, HttpMethod::GET));
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
            $route = $router->resolveRoute($this->createMockRequest($uri, HttpMethod::GET));
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
            $route = $router->resolveRoute($this->createMockRequest($uri, HttpMethod::from($method)));
            $this->assertEquals($action, $route->action());
            $this->assertEquals($uri, $route->uri());
        }
    }

    public function test_run_middlewares() {
        $middleware1 = new class {
            public function handle(Request $request, Closure $next): Response {
                $response = $next($request);
                $response->setHeader('X-Test-1', 'One Pass');
                return $response;
            }
        };

        $middleware2 = new class {
            public function handle(Request $request, Closure $next): Response {
                $response = $next($request);
                $response->setHeader('X-Test-2', 'Two Pass');
                return $response;
            }
        };

        $router = new Router();
        $uri = '/test';
        $expectedResponse = Response::text('Final Response');

        $router->get($uri, fn ($request) => $expectedResponse)
            ->setMiddlewares([$middleware1, $middleware2]);

        $response = $router->resolve($this->createMockRequest($uri, HttpMethod::GET));
        $this->assertEquals($response->headers('X-Test-1'), 'One Pass');
        $this->assertEquals($response->headers('X-Test-2'), 'Two Pass');
        $this->assertEquals($expectedResponse, $response);

    }
}
