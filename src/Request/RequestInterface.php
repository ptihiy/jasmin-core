<?php

namespace Jasmin\Core\Request;

interface RequestInterface
{
    public function isPost(): bool;

    public function isGet(): bool;

    public function getServer(): string;

    public function getPort(): string;
}
