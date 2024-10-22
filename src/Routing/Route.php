<?php

namespace Jasmin\Core\Routing;

use Exception;

class Route
{
    public const GET = "GET";
    public const POST = "POST";
    public const PUT = "PUT";
    public const DELETE = "DELETE";

    public function __construct(
        protected $path,
        protected $action,
        protected $method = self::GET,
        protected array $middleware = []
    )
    {
        
    }

    public function getPath() {
        return $this->path;
    }

    public function getMethod() {
        return $this->method;
    }

    public function resolve()
    {
        if (is_callable($this->action)) {
            return ($this->action)();
        }
        
        if (is_array($this->action) && 2 === count($this->action)) {
            list($className, $methodName) = $this->action;
            return call_user_func([new $className, $methodName]);
        }

        throw new Exception("malformed route");
    }

    public static function get(
        $path,
        $action,
        array $middleware = []
    ): self
    {
        return new self(
            $path,
            $action,
            self::GET,
            $middleware
        );
    }

    public static function post(
        $path,
        $action,
        array $middleware = []
    ): self
    {
        return new self(
            $path,
            $action,
            self::POST,
            $middleware
        );
    }

    public static function delete(
        $path,
        $action,
        array $middleware = []
    ): self
    {
        return new self(
            $path,
            $action,
            self::DELETE,
            $middleware
        );
    }
}