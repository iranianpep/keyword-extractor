<?php

namespace KeywordExtractor\Modifiers\Filters;

use PHPUnit\Framework\TestCase;

class BlacklistFilterTest extends TestCase
{
    /**
     * @dataProvider modifyTextProvider
     */
    public function testModifyText($inputText, $expected): void
    {
        $filter = new BlacklistFilter(['team', 'lead', 'net', 'leading team']);

        $this->assertEquals($expected, $filter->modifyToken($inputText));
    }

    public function modifyTextProvider(): array
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
}
