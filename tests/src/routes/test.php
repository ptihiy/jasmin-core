<?php

use Jasmin\Core\Routing\BasicRoute;
use Jasmin\Core\Routing\RouteCollection;

$route = new BasicRoute(fn () => 'nice');

$routeCollection = new RouteCollection([$route]);

return $routeCollection;