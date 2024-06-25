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
}
