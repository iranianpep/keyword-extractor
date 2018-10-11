<?php

namespace KeywordExtractor;

use PHPUnit\Framework\TestCase;

class KeywordExtractorTest extends TestCase
{
    public function testGenerateNgram()
    {
        $keywordExtractor = new KeywordExtractor();
        $input = ['this', 'is', 'an', 'example'];

        $ngrams = $keywordExtractor->generateNgrams($input, 1);
        $this->assertEquals($input, $ngrams);

        $expected = ['this is', 'is an', 'an example'];

        $ngrams = $keywordExtractor->generateNgrams($input, 2);
        $this->assertEquals($expected, $ngrams);

        $expected = ['this is an', 'is an example'];

        $ngrams = $keywordExtractor->generateNgrams($input, 3);
        $this->assertEquals($expected, $ngrams);

        $expected = ['this is an example'];

        $ngrams = $keywordExtractor->generateNgrams($input, 4);
        $this->assertEquals($expected, $ngrams);

        $input = ['this', 'is'];

        $ngrams = $keywordExtractor->generateNgrams($input, 4);
        $this->assertEquals([], $ngrams);
    }
    
    public function testRemovePunctuations()
    {
        $keywordExtractor = new KeywordExtractor();
        $text = 'this is an example.';

        $result = $keywordExtractor->removePunctuations($text);
        $this->assertEquals('this is an example', $result);

        $keywordExtractor = new KeywordExtractor();
        $text = 'this is, an example.';

        $result = $keywordExtractor->removePunctuations($text);
        $this->assertEquals('this is an example', $result);

        $keywordExtractor = new KeywordExtractor();
        $text = 'this is a text containing node.js.';

        $result = $keywordExtractor->removePunctuations($text);
        $this->assertEquals('this is a text containing nodejs', $result);
    }
}
