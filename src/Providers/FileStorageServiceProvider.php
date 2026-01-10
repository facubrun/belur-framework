<?php

namespace Belur\Providers;

use Belur\App;
use Belur\Storage\Drivers\DiskFileStorage;
use Belur\Storage\Drivers\FileStorageDriver;

use function Belur\Helpers\config;
use function Belur\Helpers\singleton;

class FileStorageServiceProvider implements ServiceProvider {
    public function registerServices(): void {
        match(config('storage.driver', 'disk')) {
            'disk' => singleton(
                FileStorageDriver::class,
                fn () => new DiskFileStorage(
                    App::$root . '/storage',
                    'storage',
                    config('app.url')
                )
            )
        };
    }
}
