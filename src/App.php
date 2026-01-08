<?php

namespace Belur;

use Belur\Database\Drivers\DatabaseDriver;
use Belur\Database\Drivers\PDODriver;
use Belur\Database\Model;
use Belur\Http\HttpMethod;
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

    public DatabaseDriver $database;

    public static function bootstrap(): App {
        $app = singleton(self::class);
        $app->router = new Router();
        $app->server = new PhpNativeServer();
        $app->request = $app->server->getRequest();
        $app->view = new BelurEngine(__DIR__ . '/../views');
        $app->session = new Session(
            new PhpNativeSessionStorage()
        );
        $app->database = singleton(DatabaseDriver::class, PDODriver::class);
        $app->database->connect(
            'mysql',
            'localhost',
            3306,
            'belur_framework',
            'root',
            ''
        );
        Model::setDatabaseDriver($app->database);
        Rule::loadDefaultRules();

        return $app;
    }

    public function prepareNextRequest(): void {
        if ($this->request->method() == HttpMethod::GET) {
            $this->session->set('_previous', $this->request->uri());
        }
    }

    public function terminate(Response $response) {
        $this->prepareNextRequest();
        $this->server->sendResponse($response);
        $this->database->close();
        exit();
    }

    public function run() {
        try {
            $response = $this->router->resolve($this->request);
            $this->terminate($response);
        } catch (HttpNotFoundException $e) {
            $this->abort(Response::text('Resource not found')->setStatus(404));
        } catch (ValidationException $e) {
            $this->abort(back()->withErrors($e->errors(), 422));
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
        $this->terminate($response);
    }

    public function session(): Session {
        return $this->session;
    }
}
