<?php

namespace KeywordExtractor\Modifiers\Filters;

use PHPUnit\Framework\TestCase;

class NumberFilterTest extends TestCase
{
    public function modifyTextProvider()
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

    /**
     * @dataProvider modifyTextProvider
     */
    public function testModifyText($inputText, $expected)
    {
        $filter = new NumberFilter();

        $this->assertEquals($expected, $filter->modifyToken($inputText));
    }
}
