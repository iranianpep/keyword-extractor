<?php

namespace KeywordExtractor\Modifiers\Transformers;

use PHPUnit\Framework\TestCase;

class TokenTransformerTest extends TestCase
{
    public function testModifyText()
    {
        $transformer = new TokenTransformer();
        $this->assertEquals(['This', 'is', 'A', 'TEST'], $transformer->modifyToken('This is A TEST'));
        $this->assertEquals(['This', 'is', 'A', 'TEST.'], $transformer->modifyToken('This is A TEST.'));
    }
}
