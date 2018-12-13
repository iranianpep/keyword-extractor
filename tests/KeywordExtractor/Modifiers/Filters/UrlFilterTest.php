<?php

namespace KeywordExtractor\Modifiers\Filters;

use PHPUnit\Framework\TestCase;

class UrlFilterTest extends TestCase
{
    public function testModifyText()
    {
        $filter = new UrlFilter();

        $inputsOutputs = [
            [
                'i' => 'http://www.example.com.au/example',
                'o' => '',
            ],
            [
                'i' => 'https://www.example.com',
                'o' => '',
            ],
            [
                'i' => 'www.example.com/example',
                'o' => 'www.example.com/example',
            ],
            [
                'i' => 'example.com',
                'o' => 'example.com',
            ],
            [
                'i' => 'www.facebook.com/example',
                'o' => 'www.facebook.com/example',
            ],
            [
                'i' => 'www.example.com',
                'o' => 'www.example.com',
            ],
            [
                'i' => 'example/example/example',
                'o' => 'example/example/example',
            ],
            [
                'i' => 'www.twitter.com/example',
                'o' => 'www.twitter.com/example',
            ],
            [
                'i' => 'example.example',
                'o' => 'example.example',
            ],
        ];

        foreach ($inputsOutputs as $inputOutput) {
            $this->assertEquals($inputOutput['o'], $filter->modifyToken($inputOutput['i']));
        }
    }
}
