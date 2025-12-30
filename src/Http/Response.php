<?php

namespace Belur\Http;

use function Belur\Helpers\app;

/**
 * HTTP Response that will be sent to the client.
 */
class Response {
    /**
     * Response HTTP status code.
     *
     * @var integer
     */
    protected int $status = 200;

    /**
     * Response HTTP headers.
     *
     * @var array
     */
    protected array $headers = [];

    /**
     * Response HTTP body.
     *
     * @var string|null
     */
    protected ?string $body = '';

    /**
     * Get response HTTP status code
     *
     * @return integer
     */
    public function status(): int {
        return $this->status;
    }

    /**
     * Set response HTTP status code
     *
     * @param integer $status
     * @return self
     */
    public function setStatus(int $status): self {
        $this->status = $status;
        return $this;
    }

    /**
     * Get response HTTP headers.
     *
     * @return array
     */
    public function headers(?string $key = null): array|string|null {
        if (is_null($key)) {
            return $this->headers;
        }
        return $this->headers[strtolower($key)] ?? null;
    }

    /**
     * Set HTTP header ´$key´ to ´$value´
     *
     * @param string $key
     * @param string $value
     * @return self
     */
    public function setHeader(string $key, string $value): self {
        $this->headers[strtolower($key)] = $value;
        return $this;
    }

    /**
     * Remove HTTP header ´$key´
     *
     * @param string $key
     * @return void
     */
    public function removeHeader(string $key) {
        unset($this->headers[strtolower($key)]);
    }

    /**
     * Set the Content-Type header to ´$value´
     *
     * @param string $value
     * @return self
     */
    public function setContentType(string $value): self {
        $this->setHeader('Content-Type', $value);
        return $this;
    }

    /**
     * Get response HTTP body.
     *
     * @return string|null
     */
    public function body(): ?string {
        return $this->body;
    }

    /**
     * Set response HTTP body.
     *
     * @param string|null $body
     * @return self
     */
    public function setBody(?string $body): self {
        $this->body = $body;
        return $this;
    }

    public function prepare() {
        if (is_null($this->body)) {
            $this->removeHeader('Content-Type');
            $this->removeHeader('Content-Length');
        } else {
            $this->setHeader('Content-Length', strlen($this->body));
        }
    }

    public static function json(mixed $data): self {
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

    public static function view(string $viewName, array $params = [], ?string $layout = null): Response {
        $content = app()->view->render($viewName, $params, $layout);

        return (new self())
         ->setContentType('text/html')
         ->setBody($content);
    }
}
