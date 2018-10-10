<?php

namespace Ngram;

use PHPUnit\Framework\TestCase;

class GreetingTest extends TestCase
{
    public function testHello()
    {
        $greeting = new Greeting();

        $this->assertEquals('hi', $greeting->hello());
    }
}
