<?php

namespace KeywordExtractor\Modifiers\Transformers;

use KeywordExtractor\Modifiers\AbstractModifier;
use KeywordExtractor\Modifiers\Tokenizers\WhitespaceTokenizer;

class TokenTransformer extends AbstractModifier
{
    public function modifyToken($text)
    {
        return (new WhitespaceTokenizer())->tokenize($text);
    }
}
