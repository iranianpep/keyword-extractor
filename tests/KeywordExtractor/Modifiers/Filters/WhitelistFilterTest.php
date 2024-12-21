<?php

namespace KeywordExtractor\Modifiers\Filters;

use PHPUnit\Framework\TestCase;

class WhitelistFilterTest extends TestCase
{
    /**
     * @dataProvider modifyTextProvider
     */
    public function testModifyText($inputText, $expected): void
    {
        $filter = new WhitelistFilter(['php', 'c#', '.net']);

        $this->assertEquals($expected, $filter->modifyToken($inputText));
    }

    public function modifyTextProvider(): array
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
}
