<?php

namespace Jasmin\Core\Kernel;

use Jasmin\Core\Container\ContainerInterface;

interface KernelInterface
{
    public function start(): void;

    public function getConfig(string $value): mixed;

    public function getContainer(): ContainerInterface;
}
