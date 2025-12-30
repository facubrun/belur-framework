<?php

namespace Belur;

use Belur\Container\Container;
use Belur\Http\HttpNotFoundException;
use Belur\Http\Request;
use Belur\Http\Response;
use Belur\Routing\Router;
use Belur\Server\PhpNativeServer;
use Belur\Server\Server;
use Belur\View\BelurEngine;
use Belur\View\View;

use function Belur\Helpers\singleton;

class App {
    public Router $router;

    public Request $request;

    public Server $server;

    public View $view;

    public static function bootstrap(): App {
        $app = singleton(self::class);
        $app->router = new Router();
        $app->server = new PhpNativeServer();
        $app->request = $app->server->getRequest();
        $app->view = new BelurEngine(__DIR__ . '/../views');

        return $app;
    }

    public function run() {
        try {
            $response = $this->router->resolve($this->request);
            $this->server->sendResponse($response);
        } catch (HttpNotFoundException $e) {
            $response = Response::text('404 Not Found')->setStatus(404);
            $this->server->sendResponse($response);
        }
    }
}
