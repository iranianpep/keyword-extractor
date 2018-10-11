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

    public function testGenerateNgram()
    {
        $greeting = new KeywordExtractor();
        $input = [
            'this',
            'is',
            'an',
            'example'
        ];

//        $ngrams = $greeting->generateNgram($input, 1);
//        $this->assertEquals($input, $ngrams);

        $expected = [
            'this is',
            'is an',
            'an example'
        ];

        $ngrams = $greeting->generateNgram($input, 2);
        $this->assertEquals($expected, $ngrams);
    }
}
