<?php

namespace KeywordExtractor\Modifiers\Filters;

use PHPUnit\Framework\TestCase;

class EmptyFilterTest extends TestCase
{
    /**
     * @dataProvider modifyTextProvider
     */
    public function testModifyText($inputText, $expected): void
    {
        $filter = new EmptyFilter();

        $this->assertEquals($expected, $filter->modifyToken($inputText));
    }

    public static function modifyTextProvider(): array
    {
        return [
            [
                '',
                '',
            ],
            [
                0,
                0,
            ],
            [
                '0',
                '0',
            ],
            [
                '0 ',
                '0 ',
            ],
            [
                'test 0',
                'test 0',
            ],
            [
                '   ',
                '',
            ],
        ];
    }

    /**
     * @dataProvider modifyArrayProvider
     */
    public function testModifyArray($inputText, $expected): void
    {
        $filter = new EmptyFilter();

        $this->assertEquals($expected, $filter->modifyTokens($inputText));
    }

    public static function modifyArrayProvider(): array
    {
        return [
            [
                [],
                [],
            ],
            [
                ['test', 1, 0, '', '0', 'c#'],
                [0 => 'test', 1 => 1, 2 => 0, 4 => '0', 5 => 'c#'],
            ],
            [
                ['test 0', 1, 0, ' test', '0', ' '],
                ['test 0', 1, 0, ' test', '0'],
            ],
        ];
    }
}
