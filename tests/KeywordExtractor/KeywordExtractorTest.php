<?php

namespace KeywordExtractor;

use PHPUnit\Framework\TestCase;

class KeywordExtractorTest extends TestCase
{
    public function testHello()
    {
        $greeting = new KeywordExtractor();

        $this->assertEquals('hi', $greeting->hello());
    }
}
