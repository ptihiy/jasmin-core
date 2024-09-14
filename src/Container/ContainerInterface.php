<?php

namespace Jasmin\Core\Container;

interface ContainerInterface
{
    public function get(string $id): mixed;

    public function has(string $id): bool;

    public function add(string $id, callable $instance): void;
}
