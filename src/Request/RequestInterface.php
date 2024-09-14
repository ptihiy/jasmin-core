<?php

namespace Jasmin\Core\Request;

interface RequestInterface
{
    public const POST = 'POST';
    public const GET = 'GET';
    public const DELETE = 'DELETE';

    public function isPost(): bool;

    public function isGet(): bool;

    public function getServer(): string;

    public function getPort(): string;

    public function getMethod(): string;

    public function getUrl(): string;

    public function file(string $name): mixed;
}
