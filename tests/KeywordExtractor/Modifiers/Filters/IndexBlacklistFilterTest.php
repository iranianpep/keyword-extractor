<?php

namespace KeywordExtractor\Modifiers\Filters;

use PHPUnit\Framework\TestCase;

class IndexBlacklistFilterTest extends TestCase
{
    /**
     * @dataProvider modifyTokensProvider
     */
    public function testModifyTokens($inputTexts, $expected): void
    {
        $filter = new IndexBlacklistFilter($inputTexts['indexes']);

        $this->assertEquals(
            $expected,
            $filter->modifyTokens($inputTexts['words'])
        );
    }

    public static function modifyTokensProvider(): array
    {
        return [
            [
                [
                    'words'   => [1, 2, 'test', '1test', ''],
                    'indexes' => [1, 3],
                ],
                [0 => 1, 2 => 'test', 4 => ''],
            ],
            [
                [
                    'words'   => [1, 2, 'test', '1test', ''],
                    'indexes' => [0, 1, 2, 3, 4, 5, 6],
                ],
                [],
            ],
            [
                [
                    'words'   => [],
                    'indexes' => [0, 1, 2, 3, 4, 5, 6],
                ],
                [],
            ],
            [
                [
                    'words'   => [1, 2, 'test', '1test', ''],
                    'indexes' => [],
                ],
                [1, 2, 'test', '1test', ''],
            ],
        ];
    }

    public function testModifyToken(): void
    {
        $filter = new IndexBlacklistFilter([1, 2, 3]);

        $this->assertEquals('dummy', $filter->modifyToken('dummy'));
    }
}
