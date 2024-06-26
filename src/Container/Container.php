<?php

namespace Jasmin\Core\Container;

use Jasmin\Core\Request\Request;
use ReflectionClass;

class Container implements ContainerInterface
{
    private array $services = [];

    public function __construct()
    {
        $this->services = [
            Request::class => fn() => new Request($_SERVER),
        ];
    }

    public function has(string $id): bool
    {
        return isset($this->services[$id]) || class_exists($id);
    }

    public function get(string $id): mixed
    {
        return isset($this->services[$id]) ? $this->services[$id] : $this->getService($id);
    }

    // see https://habr.com/ru/articles/655399/
    private function getService(string $class): object
    {
        $classReflector = new ReflectionClass($class);

        $constructorReflector = $classReflector->getConstructor();
        if (empty($constructorReflector)) {
            return new $class();
        }

        $constructorArguments = $constructorReflector->getParameters();
        if (empty($constructorArguments)) {
            return new $class();
        }

        $args = [];
        foreach ($constructorArguments as $argument) {
            $argumentType = $argument->getType()->getName();
            $args[$argument->getName()] = $this->get($argumentType);
        }

        return new $class(...$args);
    }
}
