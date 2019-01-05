<?php

namespace KeywordExtractor\Modifiers\Filters;

use PHPUnit\Framework\TestCase;

class BlacklistFilterTest extends TestCase
{
    public function modifyTextProvider()
    {
        return [
            [
                'leading',
                'leading',
            ],
            [
                'team',
                '',
            ],
            [
                'leading team',
                '',
            ],
            [
                'a leading team',
                'a leading team',
            ],
        ];
    }

    /**
     * @dataProvider modifyTextProvider
     */
    public function testModifyText($inputText, $expected)
    {
        $filter = new BlacklistFilter(['team', 'lead', 'net', 'leading team']);

        $this->assertEquals($expected, $filter->modifyToken($inputText));
    }
}
