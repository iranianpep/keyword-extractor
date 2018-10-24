<?php

namespace KeywordExtractor\Modifiers\Filters;

use PHPUnit\Framework\TestCase;

class BlacklistFilterTest extends TestCase
{
    public function testModifyText()
    {
        $filter = new BlacklistFilter(['team', 'lead', 'net', 'leading team']);

        $inputsOutputs = [
            [
                'i' => 'leading',
                'o' => 'leading',
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
                'i' => 'a leading team',
                'o' => 'a leading team',
            ],
        ];

        foreach ($inputsOutputs as $inputOutput) {
            $this->assertEquals($inputOutput['o'], $filter->modifyToken($inputOutput['i']));
        }
    }
}
