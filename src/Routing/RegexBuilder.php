<?php

namespace Jasmin\Core\Routing;

class RegexBuilder
{
    public static function compile(string $routeExpression): string
    {
        return preg_replace('/\{[^}]*\}/', '[^\/]*', $routeExpression);
    }
}