<?php

namespace KeywordExtractor\Modifiers\Filters;

use PHPUnit\Framework\TestCase;

class EmailFilterTest extends TestCase
{
    /**
     * @dataProvider modifyTextProvider
     */
    public function testModifyText($inputText, $expected): void
    {
        $filter = new EmailFilter();

        $this->assertEquals($expected, $filter->modifyToken($inputText));
    }

    public static function modifyTextProvider(): array
    {
        return [
            [
                'test@example.com.',
                '.',
            ],
            [
                'this contains an email e.g. test@example.com.',
                'this contains an email e.g. .',
            ],
            [
                'this contains an email e.g. invalid@email.',
                'this contains an email e.g. invalid@email.',
            ],
            [
                'this contains two emails valid@gmail.com and valid2@gmail.com',
                'this contains two emails  and ',
            ],
        ];
    }
}
