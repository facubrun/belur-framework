<?php

namespace Belur;

use Belur\Database\Drivers\DatabaseDriver;
use Belur\Database\Model;
use Belur\Http\HttpMethod;
use Belur\Http\HttpNotFoundException;
use Belur\Http\Request;
use Belur\Http\Response;
use Belur\Routing\Router;
use Belur\Server\PhpNativeServer;
use Belur\Server\Server;
use Belur\Session\Session;
use Belur\Validation\Exceptions\ValidationException;
use Belur\Validation\Rule;
use Belur\View\View;
use Belur\Config\Config;
use Belur\Session\SessionStorage;
use Dotenv\Dotenv;
use Throwable;

use function Belur\Helpers\app;
use function Belur\Helpers\config;
use function Belur\Helpers\singleton;

class App {
    public static string $root;

    public Router $router;

    public Request $request;

    public Server $server;

    public View $view;

    public Session $session;

    public DatabaseDriver $database;

    public static function bootstrap(string $root): App {
        self::$root = $root;
        $app = singleton(self::class);
        Rule::loadDefaultRules();

        return $app
            ->loadConfig()
            ->runServicesProviders('boot')
            ->setHttpHandlers()
            ->setUpDatabaseConnection()
            ->runServicesProviders('runtime');
    }

    protected function loadConfig(): self {
        Dotenv::createImmutable(self::$root)->load();
        Config::load(self::$root . '/config');

        return $this;
    }

    protected function runServicesProviders(string $type): self {
        foreach (config("providers.{$type}", []) as $provider) {
            $provider = new $provider();
            $provider->registerServices();
        }
        return $this;
    }

    protected function setUpDatabaseConnection(): self {
        $this->database = app(DatabaseDriver::class);
        $this->database->connect(
            config("database.connection"),
            config("database.host"),
            config("database.port"),
            config("database.database"),
            config("database.username"),
            config("database.password"),
        );
        Model::setDatabaseDriver($this->database);

        return $this;
    }

    protected function setHttpHandlers(): self {
        $this->router = new Router();
        $this->server = new PhpNativeServer();
        $this->request = singleton(Request::class, fn () => $this->server->getRequest());
        $this->session = singleton(Session::class, fn () => new Session(app(SessionStorage::class)));

        return $this;
    }

    protected function prepareNextRequest(): void {
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
