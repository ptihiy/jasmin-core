<?php

namespace test;

use PHPUnit\Framework\TestCase;
use Jasmin\Core\Request\Request;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Request::class)]
final class PostTest extends TestCase
{
    public function testRequestIsPost()
    {
        $server = [];
        $server['REQUEST_METHOD'] = 'POST';

        $post['name'] = 'Jasmin';

        $request = new Request($server, [], $post);
        $this->assertEquals('Jasmin', $request->input('name'));
    }
}
