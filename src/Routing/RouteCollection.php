<?php

namespace Jasmin\Core\Routing;

use Jasmin\Core\Routing\BasicRoute;

class RouteCollection
{
    public function __construct(
        protected array $routes = []
    ) {}

    public function addRoute(BasicRoute $route) {
        $this->routes[$route->getPath()] = $route;
    }

    public function resolve(string $path)
    {
        var_dump([1, 2], $this);

        if (array_key_exists($path, $this->routes)) {
            return $this->routes[$path]->resolve();
        }
    }
}
