<?php

namespace KeywordExtractor\Modifiers\Filters;

use PHPUnit\Framework\TestCase;

class NumberFilterTest extends TestCase
{
    /**
     * @dataProvider modifyTextProvider
     */
    public function testModifyText($inputText, $expected): void
    {
        $filter = new NumberFilter();

        $this->assertEquals($expected, $filter->modifyToken($inputText));
    }

    public static function modifyTextProvider(): array
    {
        return [
            [
                '1',
                '',
            ],
            [
                '123',
                '',
            ],
            [
                1,
                '',
            ],
            [
                123,
                '',
            ],
            [
                'test1',
                'test1',
            ],
            [
                'test 1',
                'test 1',
            ],
        ];
    }
}
