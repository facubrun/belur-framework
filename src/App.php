<?php

namespace Belur;

use Belur\Http\HttpNotFoundException;
use Belur\Http\Request;
use Belur\Http\Response;
use Belur\Routing\Router;
use Belur\Server\PhpNativeServer;
use Belur\Server\Server;

class App
{
    public Router $router;

    public Request $request;

    public Server $server;

    public function __construct()
    {
        $this->router = new Router();
        $this->server = new PhpNativeServer();
        $this->request = $this->server->getRequest();
    }

    public function run()
    {
        try {
            $route = $this->router->resolve($this->request);
            $this->request->setRoute($route);
            $action = $route->action();
            $response = $action($this->request);
            $this->server->sendResponse($response);
        } catch (HttpNotFoundException $e) {
            $response = Response::text('404 Not Found')->setStatus(404);
            $this->server->sendResponse($response);
        }
    }
}
