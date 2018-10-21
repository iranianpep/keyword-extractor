<?php

namespace KeywordExtractor\Modifiers\Filters;

use PHPUnit\Framework\TestCase;

class NumberFilterTest extends TestCase
{
    public function testModifyText()
    {
        $filter = new NumberFilter();

        $inputsOutputs = [
            [
                'i' => '1',
                'o' => '',
            ],
            [
                'i' => '123',
                'o' => '',
            ],
            [
                'i' => 1,
                'o' => '',
            ],
            [
                'i' => 123,
                'o' => '',
            ],
            [
                'i' => 'test1',
                'o' => 'test1',
            ],
            [
                'i' => 'test 1',
                'o' => 'test 1',
            ],
        ];

        foreach ($inputsOutputs as $inputOutput) {
            $this->assertEquals($inputOutput['o'], $filter->modifyText($inputOutput['i']));
        }
    }
}
