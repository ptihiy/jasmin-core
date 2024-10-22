<?php

namespace test;

use test\TestController;
use PHPUnit\Framework\TestCase;
use Jasmin\Core\Routing\Route;
use Jasmin\Core\Routing\RouteCollection;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(RouteCollection::class)]
final class RouteCollectionTest extends TestCase
{
    public function testRouteCollectionCanBeResolved()
    {
        $routeCollection = new RouteCollection();
        $routeCollection->addRoute(new Route('test-route', function () { return 'test'; }));

        $this->assertEquals('test', $routeCollection->resolve('test-route'));
    }

    public function testRouteCollectionCanBeResolvedWithSubstitution()
    {
        $routeCollection = new RouteCollection();
        $routeCollection->addRoute(new Route('test-route/{id}', function () { return 'test'; }));

        $this->assertEquals('test', $routeCollection->resolve('test-route/12'));
    }

    public function testRouteCollectionCanBeResolvedWithComplexSubstitution()
    {
        $routeCollection = new RouteCollection();
        $routeCollection->addRoute(new Route('test-route/{id}/{id2}', function () { return 'test'; }));

        $this->assertEquals('test', $routeCollection->resolve('test-route/12/14'));
    }

    public function tellRouteCollectionResolvesWithCorrectMethod()
    {
        $routeCollection = new RouteCollection();
        $routeCollection->addRoute(new Route('test-route', function () { return 'test'; }, Route::POST));

        $this->assertEquals('test', $routeCollection->resolve('test-route', Route::POST));
    }

    public function testRouteCollectionDoesntResolveWithIncorrectMethod()
    {
        $routeCollection = new RouteCollection();
        $routeCollection->addRoute(new Route('test-route', function () { return 'test'; }, Route::POST));

        $this->assertEquals(null, $routeCollection->resolve('test-route', Route::GET));
    }
}