<?php

namespace KeywordExtractor\Modifiers\Filters;

use PHPUnit\Framework\TestCase;

class PunctuationFilterTest extends TestCase
{
    /**
     * @dataProvider modifyArrayProvider
     */
    public function testModifyArray($inputText, $expected): void
    {
        $filter = new PunctuationFilter();

        $this->assertEquals($expected, $filter->modifyTokens($inputText));
    }

    public function modifyArrayProvider(): array
    {
        return [
            [
                ['test.', '.test', '.', '.test.', '', 'node.js?', 'node.js???', 'c#'],
                ['test', '.test', '', '.test', '', 'node.js', 'node.js', 'c#'],
            ],
            [
                [],
                [],
            ],
            [
                ['0', 0, '', 1],
                ['0', 0, '', 1],
            ],
            [
                ['visual studio 2018', 'knockout.js', '- knockout...js?'],
                ['visual studio 2018', 'knockout.js', '- knockout...js'],
            ],
            [
                ['(visual studio 2018', '(c#', '"c'],
                ['visual studio 2018', 'c#', 'c'],
            ],
        ];
    }

    public function testGetRightPunctuations(): void
    {
        $filter = new PunctuationFilter();
        $punctuations = ['.', ','];
        $filter->setRightPunctuations($punctuations);

        $this->assertEquals($punctuations, $filter->getRightPunctuations());
    }

    public function testGetLeftPunctuations(): void
    {
        $filter = new PunctuationFilter();
        $punctuations = ['.', ','];
        $filter->setLeftPunctuations($punctuations);

        $this->assertEquals($punctuations, $filter->getLeftPunctuations());
    }
}
