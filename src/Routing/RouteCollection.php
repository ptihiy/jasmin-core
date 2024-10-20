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
        if (array_key_exists($path, $this->routes)) {
            return $this->routes[$path]->resolve();
        } else {
            foreach ($this->routes as $route) {
                if (preg_match("|" . RegexBuilder::compile($route->getPath()) . "|", $path, $matches)) {
                    return $route->resolve();
                }
            }
        }
    }
}
