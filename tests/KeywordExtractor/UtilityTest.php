<?php

namespace KeywordExtractor;

use PHPUnit\Framework\TestCase;

class UtilityTest extends TestCase
{
    public function testFindMinDiff()
    {
        $minDiff = (new Utility())->findMinDiff([1, 2, 3, 4]);
        $this->assertEquals(1, $minDiff);

        $minDiff = (new Utility())->findMinDiff([1, 5, 3, 19, 18, 25]);
        $this->assertEquals(1, $minDiff);

        $minDiff = (new Utility())->findMinDiff([30, 5, 20, 9]);
        $this->assertEquals(4, $minDiff);

        $minDiff = (new Utility())->findMinDiff([1, 19, -4, 31, 38, 25, 100]);
        $this->assertEquals(5, $minDiff);
    }
}
