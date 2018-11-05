<?php

namespace KeywordExtractor\Modifiers;

use KeywordExtractor\Modifiers\Filters\NumberFilter;
use PHPUnit\Framework\TestCase;

class AbstractModifierTest extends TestCase
{
    public function testModify()
    {
        $filter = new NumberFilter();

        $this->assertEquals(['', 'test with number: 1980'], $filter->modify(['1980', 'test with number: 1980']));
    }
}
