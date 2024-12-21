<?php

declare(strict_types=1);

namespace KeywordExtractor\Modifiers\Tokenizers;

class WhitespaceTokenizer implements TokenizerInterface
{
    const PATTERN = '/[\pZ\pC]+/u';

    public function tokenize($str): array
    {
        return preg_split(self::PATTERN, $str, -1, PREG_SPLIT_NO_EMPTY);
    }
}

