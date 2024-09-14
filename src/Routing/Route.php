<?php

namespace Jasmin\Core\Routing;

use Jasmin\Core\Request\Request;

class Route
{
    private static array $routes = [
        Request::GET => [],
        Request::POST => [],
        Request::DELETE => []
    ];

    private static array $_middleware = [];

    public static function getRoutes(): array
    {
        return static::$routes;
    }

    public static function clearRoutes(): void
    {
        static::$routes = [
            Request::GET => [],
            Request::POST => [],
            Request::DELETE => []
        ];
    }

    public static function get(string $route, callable|array $action): void
    {
        static::$routes[Request::GET][static::replaceNamedGroups($route)] = [...$action, self::$_middleware];
    }

    public static function post(string $route, callable|array $action): void
    {
        static::$routes[Request::POST][static::replaceNamedGroups($route)] = [...$action, self::$_middleware];
    }

    public static function delete(string $route, callable|array $action): void
    {
        static::$routes[Request::DELETE][static::replaceNamedGroups($route)] = [...$action, self::$_middleware];
    }

    private static function replaceNamedGroups(string $route): string
    {
        return '~^' . preg_replace('~{([^}]+)}~', '(?P<\1>[^\/]+)', $route) . '$~';
    }

    public static function match(string $matchedRoute, string $method = Request::GET): mixed
    {
        foreach (array_keys(static::getRoutes()[$method]) as $route) {
            if (preg_match($route, $matchedRoute)) {
                return static::getRoutes()[$method][$route];
            }
        }

        return false;
    }

    public static function middleware(string $middleware, callable $func)
    {
        self::addMiddleware($middleware);
        $func();
        self::removeMiddleware($middleware);
    }

    private static function addMiddleware(string $middleware)
    {
        self::$_middleware[] = $middleware;
    }

    private static function removeMiddleware(string $middleware)
    {
        self::$_middleware = array_filter(self::$_middleware, fn($e) => $e !== $middleware);
    }
}
