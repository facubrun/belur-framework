<?php

namespace Belur\Http;

class Response {
    protected int $status = 200;
    protected array $headers = [];
    protected ?string $body = '';

    public function status(): int {
        return $this->status;
    }

    public function setStatus(int $status): self {
        $this->status = $status;
        return $this;
    }

    public function headers(): array {
        return $this->headers;
    }

    public function setHeader(string $key, string $value): self {
        $this->headers[strtolower($key)] = $value;
        return $this;
    }

    public function removeHeader(string $key) {
        unset($this->headers[strtolower($key)]);
    }

    public function setContentType(string $value): self {
        $this->setHeader('Content-Type', $value);
        return $this;
    }

    public function body(): ?string {
        return $this->body;
    }

    public function setBody(?string $body): self {
        $this->body = $body;
        return $this;
    }

    public function prepare() {
        if (is_null($this->body)) {
            $this->removeHeader('Content-Type');
            $this->removeHeader('Content-Length');
        } else {
            $this->setHeader('Content-Length',strlen($this->body));
        }
    }

    public static function json(array $data): self {
        return (new self())
         ->setContentType('application/json')
         ->setBody(json_encode($data));
    }

    public static function text(string $text): self {
        return (new self())
         ->setContentType('text/plain')
         ->setBody($text);
    }

    public static function redirect(string $url): self {
        return (new self())
         ->setStatus(302)
         ->setHeader('Location', $url);
    }
}