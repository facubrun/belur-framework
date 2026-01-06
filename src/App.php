<?php

namespace Belur;

use Belur\Http\HttpNotFoundException;
use Belur\Http\Request;
use Belur\Http\Response;
use Belur\Routing\Router;
use Belur\Server\PhpNativeServer;
use Belur\Server\Server;
use Belur\Session\PhpNativeSessionStorage;
use Belur\Session\Session;
use Belur\Validation\Exceptions\ValidationException;
use Belur\Validation\Rule;
use Belur\View\BelurEngine;
use Belur\View\View;
use Throwable;

use function Belur\Helpers\singleton;

class App {
    public Router $router;

    public Request $request;

    public Server $server;

    public View $view;

    public Session $session;

    public static function bootstrap(): App {
        $app = singleton(self::class);
        $app->router = new Router();
        $app->server = new PhpNativeServer();
        $app->request = $app->server->getRequest();
        $app->view = new BelurEngine(__DIR__ . '/../views');
        $app->session = new Session(
            new PhpNativeSessionStorage()
        );
        Rule::loadDefaultRules();

        return $app;
    }

    public function run() {
        try {
            $response = $this->router->resolve($this->request);
            $this->server->sendResponse($response);
        } catch (HttpNotFoundException $e) {
            $this->abort(Response::text('Resource not found')->setStatus(404));
        } catch (ValidationException $e) {
            $this->abort(Response::json($e->errors())->setStatus(422));
        } catch (Throwable $e) {
            $response = Response::json([
                'error' => $e::class,
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);
            $this->abort($response->setStatus(500));
        }
    }

    public function abort(Response $response) {
        $this->server->sendResponse($response);
    }
}
