<?php

namespace test;

use Jasmin\Core\Routing\RegexBuilder;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(RegexBuilder::class)]
final class RegexBuilderTest extends TestCase
{
    public function testRegexBuilderReturnsSimpleExpressionWithoutBrackets()
    {
        $this->assertEquals('test', RegexBuilder::compile('test'));
    }

    public function testRegexBuilderReturnsComplexExpressionWithoutBrackets()
    {
        $this->assertEquals('test/test/2', RegexBuilder::compile('test/test/2'));
    }

    public function testRegexBuilderReturnsComplexExpressionWithBrackets()
    {
        $this->assertEquals('test/[^\/]*', RegexBuilder::compile('test/{id}'));
    }
}    
