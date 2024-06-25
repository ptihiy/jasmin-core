<?php

namespace Jasmin\Core\Request;

class Request implements RequestInterface
{
    public const POST = 'POST';
    public const GET = 'GET';

    private $requestMethod = null;

    public function __construct(array $server)
    {
        $this->setRequestData($server);
    }

    private function setRequestData(array $server): void
    {
        if (array_key_exists('REQUEST_METHOD', $server)) {
            $this->requestMethod = $server['REQUEST_METHOD'];
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
}
