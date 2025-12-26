<?php

namespace Belur\Http;

class Response {
    protected int $status = 200;
    protected array $headers = [];
    protected ?string $body = '';

    public function status(): int {
        return $this->status;
    }

    public function setStatus(int $status): void {
        $this->status = $status;
    }

    public function headers(): array {
        return $this->headers;
    }

    public function setHeader(string $key, string $value): void {
        $this->headers[strtolower($key)] = $value;
    }

    public function removeHeader(string $key) {
        unset($this->headers[strtolower($key)]);
    }

    public function body(): ?string {
        return $this->body;
    }

    public function setBody(?string $body) {
        $this->body = $body;
    }

    public function prepare() {
        if (is_null($this->body)) {
            $this->removeHeader('Content-Type');
            $this->removeHeader('Content-Length');
        } else {
            $this->setHeader('Content-Length',strlen($this->body));
        }
    }
}