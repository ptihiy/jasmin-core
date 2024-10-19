<?php

namespace test;

use test\TestController;
use PHPUnit\Framework\TestCase;
use Jasmin\Core\Routing\BasicRoute;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(BasicRoute::class)]
final class RouteTest extends TestCase
{
    public function testRouteCanProvidePath()
    {
        $route = new BasicRoute('test-route', fn() => 'test');

        $this->assertEquals('test-route', $route->getPath());
    }

    public function testRouteCanProvideMethod()
    {
        $route = new BasicRoute('test-route', fn() => 'test');

        $this->assertEquals(BasicRoute::GET, $route->getMethod());
    }

    public function testRouteCanProvidePostMethod()
    {
        $route = BasicRoute::post('test-route', fn() => 'test');

        $this->assertEquals(BasicRoute::POST, $route->getMethod());
    }
    
    public function testRouteCanProvideDeleteMethod()
    {
        $route = BasicRoute::delete('test-route', fn() => 'test');

        $this->assertEquals(BasicRoute::DELETE, $route->getMethod());
    } 

    public function testRouteCanBeResolvedWithClosure()
    {
        $route = new BasicRoute('test-route', fn() => 'test');

        $this->assertEquals('test', $route->resolve());
    }

    public function testGetRouteCanBeResolvedWithClosure()
    {
        $route = BasicRoute::get('test-route', fn() => 'test');

        $this->assertEquals('test', $route->resolve());
    }

    public function testRouteCanBeResolvedWithControllerMethod()
    {
        $route = new BasicRoute('test-route', [TestController::class, 'handle']);

        $this->assertEquals('test', $route->resolve());
    }
}