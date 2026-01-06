<?php

namespace Belur\Session;

class Session implements SessionStorage {
    protected SessionStorage $storage;
    
    public function __construct(SessionStorage $storage) {
        $this->storage = $storage; // para saber que tipo de almacenamiento usar
        $this->storage->start();
    }

    public function start() {
        return $this->storage->start();
    }

    public function flash(string $key, mixed $value) {
        //
    }

    public function id(): string {
        return $this->storage->id();
    }

    public function get(string $key, $default = null) {
        return $this->storage->get($key, $default);
    }

    public function set(string $key, mixed $value) {
        $this->storage->set($key, $value);
    }

    public function has(string $key): bool {
        return $this->storage->has($key);
    }

    public function remove(string $key) {
        $this->storage->remove($key);
    }

    public function destroy() {
        $this->storage->destroy();
    }
}
