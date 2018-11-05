<?php

namespace KeywordExtractor\Modifiers\Filters;

use PHPUnit\Framework\TestCase;

class IndexBlacklistFilterTest extends TestCase
{
    public function testModifyTokens()
    {
        $inputsOutputs = [
            [
                'i' => [
                    'words'   => [1, 2, 'test', '1test', ''],
                    'indexes' => [1, 3],
                ],
                'o' => [0 => 1, 2 => 'test', 4 => ''],
            ],
            [
                'i' => [
                    'words'   => [1, 2, 'test', '1test', ''],
                    'indexes' => [0, 1, 2, 3, 4, 5, 6],
                ],
                'o' => [],
            ],
            [
                'i' => [
                    'words'   => [],
                    'indexes' => [0, 1, 2, 3, 4, 5, 6],
                ],
                'o' => [],
            ],
            [
                'i' => [
                    'words'   => [1, 2, 'test', '1test', ''],
                    'indexes' => [],
                ],
                'o' => [1, 2, 'test', '1test', ''],
            ],
        ];

        foreach ($inputsOutputs as $inputOutput) {
            $filter = new IndexBlacklistFilter($inputOutput['i']['indexes']);

            $this->assertEquals(
                $inputOutput['o'],
                $filter->modifyTokens($inputOutput['i']['words'])
            );
        }
    }

    public function testModifyToken()
    {
        $filter = new IndexBlacklistFilter([1, 2, 3]);
        $this->assertEquals('dummy', $filter->modifyToken('dummy'));
    }
}
