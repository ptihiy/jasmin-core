<?php

namespace test;

use Jasmin\Core\Routing\Route;
use PHPUnit\Framework\TestCase;
use Jasmin\Core\Request\Request;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Route::class)]
final class RouteTest extends TestCase
{
    public function testAddGetRouteWorks()
    {
        Route::clearRoutes();
        Route::get('test', fn() => 'great!');
        $routes = [
            Request::GET => [ '~^test$~' => fn() => 'great!' ],
            Request::POST => [],
            Request::DELETE => []
        ];

        $this->assertEquals($routes, Route::getRoutes());
    }

    public function testClearRoutesWorks()
    {
        Route::get('test', fn() => 'great!');
        Route::clearRoutes();

        $routes = [
        Request::GET => [],
        Request::POST => [],
        Request::DELETE => []
        ];
        $this->assertEquals($routes, Route::getRoutes());
    }

    public function testBasicNamedGroupWorks()
    {
        Route::clearRoutes();
        Route::get('news/{id}', fn() => 'great!');
        $routes = [
            Request::GET => [ '~^news/(?P<id>[^\/]+)$~' => fn() => 'great!' ],
            Request::POST => [],
            Request::DELETE => []
        ];
        $this->assertEquals($routes, Route::getRoutes());
    }

    public function testMatchWithoutNamedGroupWorks()
    {
        Route::clearRoutes();
        Route::get('test', fn() => 'great!');
        $this->assertTrue(Route::match('test', Request::GET));
    }

    public function testMatchWithNamedGroupWorks()
    {
        Route::clearRoutes();
        Route::get('news/{id}', fn() => 'great!');
        $this->assertTrue(Route::match('news/12', Request::GET));
    }
}
