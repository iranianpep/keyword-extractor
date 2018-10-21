<?php

namespace KeywordExtractor\Modifiers\Transformers;

class LowerCaseTransformer extends AbstractTransformer
{
    public function modifyText($text)
    {
        return mb_strtolower($text, 'utf-8');
    }
}
