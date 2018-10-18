<?php

namespace KeywordExtractor;

use PHPUnit\Framework\TestCase;

class FilterTest extends TestCase
{
    public function testRemovePunctuations()
    {
        $filter = new Filter();

        $inputsOutputs = [
            [
                'i' => ['test.', '.test', '.', '.test.', '', 'node.js?', 'node.js???'],
                'o' => ['test', 'test', '', 'test', '', 'node.js', 'node.js']
            ],
            [
                'i' => [],
                'o' => []
            ],
            [
                'i' => ['0', 0, '', 1],
                'o' => ['0', 0, '', 1]
            ],
            [
                'i' => ['visual studio 2018', 'knockout.js', '- knockout...js?'],
                'o' => ['visual studio 2018', 'knockout.js', 'knockout...js']
            ]
        ];

        foreach ($inputsOutputs as $inputOutput) {
            $this->assertEquals($inputOutput['o'], $filter->removePunctuations($inputOutput['i']));
        }
    }

    public function testRemoveNumbers()
    {
        $filter = new Filter();

        $inputsOutputs = [
            [
                'i' => ['1'],
                'o' => []
            ],
            [
                'i' => [1],
                'o' => []
            ],
            [
                'i' => [1, 2, '1', '0', 0, '', 'test1', '1test', 'test1test'],
                'o' => ['', 'test1', '1test', 'test1test']
            ],
            [
                'i' => [],
                'o' => []
            ]
        ];

        foreach ($inputsOutputs as $inputOutput) {
            $this->assertEquals($inputOutput['o'], $filter->removeNumbers($inputOutput['i']));
        }
    }

    public function testRemoveWordsByIndexes()
    {
        $filter = new Filter();

        $inputsOutputs = [
            [
                'i' => [
                    'words' => [1, 2, 'test', '1test', ''],
                    'indexes' => [1, 3],
                ],
                'o' => [1, 'test', '']
            ],
            [
                'i' => [
                    'words' => [1, 2, 'test', '1test', ''],
                    'indexes' => [0, 1, 2, 3, 4, 5, 6],
                ],
                'o' => []
            ],
            [
                'i' => [
                    'words' => [],
                    'indexes' => [0, 1, 2, 3, 4, 5, 6],
                ],
                'o' => []
            ],
            [
                'i' => [
                    'words' => [1, 2, 'test', '1test', ''],
                    'indexes' => [],
                ],
                'o' => [1, 2, 'test', '1test', '']
            ],
        ];

        foreach ($inputsOutputs as $inputOutput) {
            $this->assertEquals(
                $inputOutput['o'],
                $filter->removeWordsByIndexes($inputOutput['i']['words'], $inputOutput['i']['indexes'])
            );
        }
    }
}
