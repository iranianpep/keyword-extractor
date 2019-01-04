<?php

namespace KeywordExtractor\Modifiers\Filters;

use PHPUnit\Framework\TestCase;

class WhitelistFilterTest extends TestCase
{
    public function modifyTextProvider()
    {
        return [
            [
                'leading',
                '',
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
                'c# dev',
                '',
            ],
            [
                'c#',
                'c#',
            ],
            [
                'php',
                'php',
            ],
            [
                'a leading team',
                '',
            ],
        ];
    }

    /**
     * @dataProvider modifyTextProvider
     */
    public function testModifyText($inputText, $expected)
    {
        $filter = new WhitelistFilter(['php', 'c#', '.net']);

        $this->assertEquals($expected, $filter->modifyToken($inputText));
    }
}
