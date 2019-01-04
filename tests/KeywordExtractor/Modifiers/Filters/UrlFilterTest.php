<?php

namespace KeywordExtractor\Modifiers\Filters;

use PHPUnit\Framework\TestCase;

class UrlFilterTest extends TestCase
{
    public function modifyTextProvider()
    {
        return [
            [
                'http://www.example.com.au/example',
                '',
            ],
            [
                'https://www.example.com',
                '',
            ],
            [
                'www.example.com/example',
                'www.example.com/example',
            ],
            [
                'example.com',
                'example.com',
            ],
            [
                'www.facebook.com/example',
                'www.facebook.com/example',
            ],
            [
                'www.example.com',
                'www.example.com',
            ],
            [
                'example/example/example',
                'example/example/example',
            ],
            [
                'www.twitter.com/example',
                'www.twitter.com/example',
            ],
            [
                'example.example',
                'example.example',
            ],
        ];
    }

    /**
     * @dataProvider modifyTextProvider
     */
    public function testModifyText($inputText, $expected)
    {
        $filter = new UrlFilter();

        $this->assertEquals($expected, $filter->modifyToken($inputText));
    }
}
