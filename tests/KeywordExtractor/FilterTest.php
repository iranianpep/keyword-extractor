<?php

namespace KeywordExtractor;

use PHPUnit\Framework\TestCase;

class FilterTest extends TestCase
{
    public function testRemoveWordsByIndexes()
    {
        $filter = new Filter();

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
            $this->assertEquals(
                $inputOutput['o'],
                $filter->removeWordsByIndexes($inputOutput['i']['words'], $inputOutput['i']['indexes'])
            );
        }
    }

    public function testRemoveEmptyArrayElements()
    {
        $filter = new Filter();

        $inputsOutputs = [
            [
                'i' => [],
                'o' => [],
            ],
            [
                'i' => ['test', 1, 0, '', '0', 'c#'],
                'o' => ['test', 1, 0, '0', 'c#'],
            ],
            [
                'i' => ['test 0', 1, 0, ' test', '0', ' '],
                'o' => ['test 0', 1, 0, ' test', '0'],
            ],
        ];

        foreach ($inputsOutputs as $inputOutput) {
            $this->assertEquals($inputOutput['o'], $filter->removeEmptyArrayElements($inputOutput['i']));
        }
    }
}
