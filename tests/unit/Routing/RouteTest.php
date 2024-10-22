<?php

namespace test;

use test\TestController;
use PHPUnit\Framework\TestCase;
use Jasmin\Core\Routing\Route;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Route::class)]
final class RouteTest extends TestCase
{
    public function testRouteCanProvidePath()
    {
        $route = new Route('test-route', fn() => 'test');

        $this->assertEquals('test-route', $route->getPath());
    }

    public function testRouteCanProvideMethod()
    {
        $route = new Route('test-route', fn() => 'test');

        $this->assertEquals(Route::GET, $route->getMethod());
    }

    public function testRouteCanProvidePostMethod()
    {
        $route = Route::post('test-route', fn() => 'test');

        $this->assertEquals(Route::POST, $route->getMethod());
    }
    
    public function testRouteCanProvideDeleteMethod()
    {
        $route = Route::delete('test-route', fn() => 'test');

        $this->assertEquals(Route::DELETE, $route->getMethod());
    } 

    public function testRouteCanBeResolvedWithClosure()
    {
        $route = new Route('test-route', fn() => 'test');

        $this->assertEquals('test', $route->resolve());
    }

    public function testGetRouteCanBeResolvedWithClosure()
    {
        $route = Route::get('test-route', fn() => 'test');

        $this->assertEquals('test', $route->resolve());
    }

    public function testRouteCanBeResolvedWithControllerMethod()
    {
        $route = new Route('test-route', [TestController::class, 'handle']);

        $this->assertEquals('test', $route->resolve());
    }
}