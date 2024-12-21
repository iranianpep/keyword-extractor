<?php

namespace KeywordExtractor\Modifiers\Transformers;

use PHPUnit\Framework\TestCase;

class TokenTransformerTest extends TestCase
{
    /**
     * @dataProvider modifyTextProvider
     */
    public function testModifyText($expected, $inputText): void
    {
        $transformer = new TokenTransformer();

        $this->assertEquals($expected, $transformer->modifyToken($inputText));
    }

    public function modifyTextProvider(): array
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
}
