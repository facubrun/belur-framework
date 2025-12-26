<?php

namespace Belur\Tests;

use Belur\Route;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class RouteTest extends TestCase {

    public static function routesWithNoParameters() {
        return [
            ['/'],
            ['/test'],
            ['/some/longer/path'],
            ['/another/test/path/here'],
        ];
    }

    #[DataProvider('routesWithNoParameters')]
    public function test_regex_with_no_parameters(string $uri) {
        $route = new Route($uri, fn () => 'test');
        $this->assertTrue($route->matches($uri));
        $this->assertFalse($route->matches($uri . '/other/path'));
        $this->assertFalse($route->matches('$/some/path/' . $uri));
        $this->assertFalse($route->matches('/random'));
    }

    #[DataProvider('routesWithNoParameters')]
    public function test_no_parameters(string $uri) {
        $route = new Route($uri, fn () => 'test');
        $this->assertTrue($route->matches($uri .'/'));
    }

    public static function routesWithParameters() {
        return [
            [
                '/test/{test}', 
                '/test/123',
                ['test' => '123']
            ],
            [
                '/post/{postId}/comment/{commentId}',
                '/post/45/comment/67',
                ['postId' => '45', 'commentId' => '67']
            ],
            [
                '/category/{categoryName}/item/{itemId}',
                '/category/electronics/item/890',
                ['categoryName' => 'electronics', 'itemId' => '890']
            ],
            [
                '/product/{productId}/review/{reviewId}/detail',
                '/product/555/review/999/detail',
                ['productId' => '555', 'reviewId' => '999']
            ],
        ];
    }

    #[DataProvider('routesWithParameters')]
    public function test_regex_with_parameters(string $definition, string $uri, array $expectedParams) {
        $route = new Route($definition, fn () => 'test');
        $this->assertTrue($route->matches($uri));
        $this->assertFalse($route->matches('$uri/other/path'));
        $this->assertFalse($route->matches('$/some/path/$uri'));
        $this->assertFalse($route->matches('/random'));
    }

    #[DataProvider('routesWithParameters')]
    public function test_parse_parameters(string $definition, string $uri, array $expectedParams) {
        $route = new Route($definition, fn () => 'test');
        $this->assertTrue($route->hasParameters()); // asegurarse que la ruta tiene parametros
        $this->assertEquals($expectedParams, $route->parseParameters($uri));
    }
    
}