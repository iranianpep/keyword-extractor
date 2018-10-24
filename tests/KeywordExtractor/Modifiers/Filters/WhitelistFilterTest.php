<?php

namespace KeywordExtractor\Modifiers\Filters;

use PHPUnit\Framework\TestCase;

class WhitelistFilterTest extends TestCase
{
    public function testModifyText()
    {
        $filter = new WhitelistFilter(['php', 'c#', '.net']);

        $inputsOutputs = [
            [
                'i' => 'leading',
                'o' => '',
            ],
            [
                'i' => 'team',
                'o' => '',
            ],
            [
                'i' => 'leading team',
                'o' => '',
            ],
            [
                'i' => 'c# dev',
                'o' => '',
            ],
            [
                'i' => 'c#',
                'o' => 'c#',
            ],
            [
                'i' => 'php',
                'o' => 'php',
            ],
            [
                'i' => 'a leading team',
                'o' => '',
            ],
        ];

        foreach ($inputsOutputs as $inputOutput) {
            $this->assertEquals($inputOutput['o'], $filter->modifyToken($inputOutput['i']));
        }
    }
}
