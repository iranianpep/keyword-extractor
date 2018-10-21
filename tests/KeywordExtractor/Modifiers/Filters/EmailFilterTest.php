<?php

namespace KeywordExtractor\Modifiers\Filters;

use PHPUnit\Framework\TestCase;

class EmailFilterTest extends TestCase
{
    public function testModifyText()
    {
        $filter = new EmailFilter();

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
            $this->assertEquals($inputOutput['o'], $filter->modifyText($inputOutput['i']));
        }
    }
}
