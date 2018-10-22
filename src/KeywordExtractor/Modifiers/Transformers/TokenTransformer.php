<?php

namespace KeywordExtractor\Modifiers\Transformers;

use NlpTools\Tokenizers\WhitespaceTokenizer;

class TokenTransformer extends AbstractTransformer
{
    public function modifyText($text)
    {
        return (new WhitespaceTokenizer())->tokenize($text);
    }
}
