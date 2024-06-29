<?php

namespace Jasmin\Core\Request;

class Request implements RequestInterface
{
    public const POST = 'POST';
    public const GET = 'GET';

    private $requestMethod = null;
    private $server = null;
    private $port = null;
    private $url = null;

    public function __construct(array $server)
    {
        $this->setRequestData($server);
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
    }

    public function isPost(): bool
    {
        return $this->requestMethod === Request::POST;
    }

    public function isGet(): bool
    {
        return $this->requestMethod === Request::GET;
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
        return $this->url;
    }
}
