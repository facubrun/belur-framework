<?php

namespace Belur\Storage;

use Belur\Storage\Drivers\FileStorageDriver;

use function Belur\Helpers\app;

/**
 * File storage utilities.
 */
class Storage {
    /**
     * Put file in the storage directory.
     *
     * @param string $path
     * @param mixed $content
     * @return string URL of the file.
     */
    public static function put(string $path, mixed $content): string {
        return app(FileStorageDriver::class)->put($path, $content);
    }
}
