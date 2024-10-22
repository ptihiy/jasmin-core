<?php

namespace Jasmin\Core\Routing;

use Jasmin\Core\Routing\Route;

class RouteCollection
{
    public function __construct(
        protected array $routes = [
            Route::GET => [],
            Route::POST => [],
            Route::PUT => [],
            Route::DELETE => [],
        ]
    ) {}

    public function addRoute(Route $route) {
        $this->routes[$route->getMethod()][$route->getPath()] = $route;
    }

    public function resolve(string $path, string $method = Route::GET)
    {
        if (array_key_exists($path, $this->routes[$method])) {
            return $this->routes[$method][$path]->resolve();
        } else {
            foreach ($this->routes[$method] as $route) {
                if (preg_match("|" . RegexBuilder::compile($route->getPath()) . "|", $path, $matches)) {
                    return $route->resolve();
                }
            }
        }
    }
}
