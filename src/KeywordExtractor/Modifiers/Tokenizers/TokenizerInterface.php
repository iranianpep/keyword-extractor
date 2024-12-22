<?php

namespace KeywordExtractor\Modifiers\Tokenizers;

interface TokenizerInterface
{
    /**
     * Break a character sequence to a sequence of tokens.
     *
     * @param string $str Text to be tokenized
     *
     * @return array List of tokens from the string
     */
    public function tokenize($str): array;
}
