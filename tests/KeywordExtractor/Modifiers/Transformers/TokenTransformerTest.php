<?php

namespace KeywordExtractor\Modifiers\Transformers;

use PHPUnit\Framework\TestCase;

class TokenTransformerTest extends TestCase
{
    public function modifyTextProvider()
    {
        return [
            [
                ['This', 'is', 'A', 'TEST'],
                'This is A TEST',
            ],
            [
                ['This', 'is', 'A', 'TEST.'],
                'This is A TEST.',
            ],
        ];
    }

    /**
     * @dataProvider modifyTextProvider
     */
    public function testModifyText($expected, $inputText)
    {
        $transformer = new TokenTransformer();

        $this->assertEquals($expected, $transformer->modifyToken($inputText));
    }
}
