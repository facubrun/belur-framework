<?php

namespace Belur\Providers;

use Belur\Server\PhpNativeServer;
use Belur\Server\Server;

use function Belur\Helpers\singleton;

class ServerServiceProvider implements ServiceProvider {
    public function registerServices(): void {
        singleton(Server::class, PhpNativeServer::class);
    }
}
