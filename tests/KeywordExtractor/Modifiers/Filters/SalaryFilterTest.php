<?php

namespace KeywordExtractor\Modifiers\Filters;

use PHPUnit\Framework\TestCase;

class SalaryFilterTest extends TestCase
{
    public function testModifyText()
    {
        $filter = new SalaryFilter();

        $inputsOutputs = [
            [
                'i' => 'test123',
                'o' => 'test123',
            ],
            [
                'i' => '123test',
                'o' => '123test',
            ],
            [
                'i' => 'test123test',
                'o' => 'test123test',
            ],
            [
                'i' => '12345',
                'o' => '',
            ],
            [
                'i' => '12.34',
                'o' => '',
            ],
            [
                'i' => '12.3456',
                'o' => '',
            ],
            [
                'i' => '$40',
                'o' => '',
            ],
            [
                'i' => '$119,921.00',
                'o' => '',
            ],
            [
                'i' => '$27.50',
                'o' => '',
            ],
            [
                'i' => '$490.5',
                'o' => '',
            ],
            [
                'i' => '$5',
                'o' => '',
            ],
            [
                'i' => '$65,100',
                'o' => '',
            ],
            [
                'i' => '$76,611',
                'o' => '',
            ],
            [
                'i' => '$70k',
                'o' => '',
            ],
            [
                'i' => '$150k',
                'o' => '',
            ],
            [
                'i' => '$150hr',
                'o' => '',
            ],
            [
                'i' => '$150hour',
                'o' => '',
            ],
            [
                'i' => '$150/hr',
                'o' => '',
            ],
            [
                'i' => '$150/hour',
                'o' => '',
            ],
            [
                'i' => '150/hour',
                'o' => '',
            ],
            [
                'i' => 'example/hour',
                'o' => 'example/hour',
            ],
            [
                'i' => 'c#',
                'o' => 'c#',
            ],
        ];

        foreach ($inputsOutputs as $inputOutput) {
            $this->assertEquals($inputOutput['o'], $filter->modifyToken($inputOutput['i']));
        }
    }
}
