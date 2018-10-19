<?php

namespace KeywordExtractor;

use PHPUnit\Framework\TestCase;

class FilterTest extends TestCase
{
    public function testRemoveRightPunctuations()
    {
        $filter = new Filter();

        $inputsOutputs = [
            [
                'i' => ['test.', '.test', '.', '.test.', '', 'node.js?', 'node.js???', 'c#'],
                'o' => ['test', '.test', '', '.test', '', 'node.js', 'node.js', 'c#'],
            ],
            [
                'i' => [],
                'o' => [],
            ],
            [
                'i' => ['0', 0, '', 1],
                'o' => ['0', 0, '', 1],
            ],
            [
                'i' => ['visual studio 2018', 'knockout.js', '- knockout...js?'],
                'o' => ['visual studio 2018', 'knockout.js', '- knockout...js'],
            ],
        ];

        foreach ($inputsOutputs as $inputOutput) {
            $this->assertEquals($inputOutput['o'], $filter->removeRightPunctuations($inputOutput['i']));
        }
    }

    public function testRemoveNumbers()
    {
        $filter = new Filter();

        $inputsOutputs = [
            [
                'i' => ['1'],
                'o' => [],
            ],
            [
                'i' => [1],
                'o' => [],
            ],
            [
                'i' => [1, 2, '1', '0', 0, '', 'test1', '1test', 'test1test', 'c#'],
                'o' => ['', 'test1', '1test', 'test1test', 'c#'],
            ],
            [
                'i' => [],
                'o' => [],
            ],
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
                    'words'   => [1, 2, 'test', '1test', ''],
                    'indexes' => [1, 3],
                ],
                'o' => [1, 'test', ''],
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

    public function testRemoveEmails()
    {
        $filter = new Filter();

        $inputsOutputs = [
            [
                'i' => 'test@example.com.',
                'o' => '.',
            ],
            [
                'i' => 'this contains an email e.g. test@example.com.',
                'o' => 'this contains an email e.g. .',
            ],
            [
                'i' => 'this contains an email e.g. invalid@email.',
                'o' => 'this contains an email e.g. invalid@email.',
            ],
            [
                'i' => 'this contains two emails valid@gmail.com and valid2@gmail.com',
                'o' => 'this contains two emails  and ',
            ],
        ];

        foreach ($inputsOutputs as $inputOutput) {
            $this->assertEquals($inputOutput['o'], $filter->removeEmails($inputOutput['i']));
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
