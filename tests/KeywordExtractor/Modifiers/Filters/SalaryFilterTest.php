<?php

namespace KeywordExtractor\Modifiers\Filters;

use PHPUnit\Framework\TestCase;

class SalaryFilterTest extends TestCase
{
    public function modifyTextProvider()
    {
        return [
            [
                'test123',
                'test123',
            ],
            [
                '123test',
                '123test',
            ],
            [
                'test123test',
                'test123test',
            ],
            [
                '12345',
                '',
            ],
            [
                '12.34',
                '',
            ],
            [
                '12.3456',
                '',
            ],
            [
                '$40',
                '',
            ],
            [
                '$119,921.00',
                '',
            ],
            [
                '$27.50',
                '',
            ],
            [
                '$490.5',
                '',
            ],
            [
                '$5',
                '',
            ],
            [
                '$65,100',
                '',
            ],
            [
                '$76,611',
                '',
            ],
            [
                '$70k',
                '',
            ],
            [
                '$150k',
                '',
            ],
            [
                '$27ph',
                '',
            ],
            [
                '$3b',
                '',
            ],
            [
                '$5m',
                '',
            ],
            [
                '$850/day',
                '',
            ],
            [
                '$150hr',
                '',
            ],
            [
                '$150hour',
                '',
            ],
            [
                '$150/hr',
                '',
            ],
            [
                '$150/hour',
                '',
            ],
            [
                '150/hour',
                '',
            ],
            [
                'example/hour',
                'example/hour',
            ],
            [
                'c#',
                'c#',
            ],
        ];
    }

    /**
     * @dataProvider modifyTextProvider
     */
    public function testModifyText($inputText, $expected)
    {
        $filter = new SalaryFilter();

        $this->assertEquals($expected, $filter->modifyToken($inputText));
    }
}
