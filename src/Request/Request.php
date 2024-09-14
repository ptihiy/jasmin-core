<?php

namespace Jasmin\Core\Request;

class Request implements RequestInterface
{
    private $requestMethod = null;
    private $server = null;
    private $port = null;
    private $url = null;
    private $get = [];
    private $post = [];
    protected $files = [];
    private $referer = null;

    public function __construct(array $server, array $get = [], array $post = [], array $files = [])
    {
        $this->setRequestData($server);
        $this->get = $get;
        $this->post = $post;
        $this->files = $files;
    }

    private function setRequestData(array $server): void
    {
        if (array_key_exists('REQUEST_METHOD', $server)) {
            $this->requestMethod = $server['REQUEST_METHOD'];
        }

        if (array_key_exists('SERVER_NAME', $server)) {
            $this->server = $server['SERVER_NAME'];
        }

        if (array_key_exists('SERVER_PORT', $server)) {
            $this->port = $server['SERVER_PORT'];
        }

        if (array_key_exists('REQUEST_URI', $server)) {
            $this->url = $server['REQUEST_URI'];
        }


        if (array_key_exists('HTTP_REFERER', $server)) {
            $this->referer = $server['HTTP_REFERER'];
        }
    }

    public function isPost(): bool
    {
        return $this->requestMethod === Request::POST;
    }

    public function isGet(): bool
    {
        return $this->requestMethod === Request::GET;
    }

    public function getMethod(): string
    {
        return $this->requestMethod;
    }

    public function getServer(): string
    {
        return $this->server;
    }

    public function getPort(): string
    {
        return $this->port;
    }

    public function getUrl(): string
    {
        return trim($this->url, '/');
    }

    public function input($name, $default = null): mixed
    {
        return array_key_exists($name, $this->post) ? $this->post[$name] : $default;
    }

    public function post()
    {
        return $this->post;
    }

    public function file(string $name): mixed
    {
        return $this->files[$name];
    }

    public function referer(): ?string
    {
        return $this->referer;
    }
}
