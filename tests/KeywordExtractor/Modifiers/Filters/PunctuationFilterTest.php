<?php

namespace KeywordExtractor\Modifiers\Filters;

use PHPUnit\Framework\TestCase;

class PunctuationFilterTest extends TestCase
{
    public function testModifyArray()
    {
        $filter = new PunctuationFilter();

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
            $this->assertEquals($inputOutput['o'], $filter->modifyTokens($inputOutput['i']));
        }
    }

    public function testGetPunctuations()
    {
        $filter = new PunctuationFilter();
        $punctuations = ['.', ','];
        $filter->setPunctuations($punctuations);

        $this->assertEquals($punctuations, $filter->getPunctuations());
    }
}
