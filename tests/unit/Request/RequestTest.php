<?php

namespace test;

use PHPUnit\Framework\TestCase;
use Jasmin\Core\Request\Request;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Request::class)]
final class RequestTest extends TestCase
{
    public function testRequestIsPost()
    {
        $server = [];
        $server['REQUEST_METHOD'] = 'POST';

        $request = new Request($server);
        $this->assertTrue($request->isPost());
    }

    public function testRequestIsGet()
    {
        $server = [];
        $server['REQUEST_METHOD'] = 'GET';

        $request = new Request($server);
        $this->assertTrue($request->isGet());
    }

    public function testRequestHaveServerInfo()
    {
        $server = [];
        $server['SERVER_NAME'] = 'localhost';

        $request = new Request($server);
        $this->assertEquals('localhost', $request->getServer());
    }

    public function testRequestHaveUrlInfo()
    {
        $server = [];
        $server['REQUEST_URI'] = '/index.html';

        $request = new Request($server);
        $this->assertEquals('/index.html', $request->getUrl());
    }

    public function testRequestReturnsMethod()
    {
        $server = [];
        $server['REQUEST_METHOD'] = 'POST';

        $request = new Request($server);
        $this->assertEquals('POST', $request->getMethod());
    }
}
