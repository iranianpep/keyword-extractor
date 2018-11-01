<?php

namespace KeywordExtractor\Modifiers\Transformers;

use KeywordExtractor\Modifiers\AbstractModifier;

class LowerCaseTransformer extends AbstractModifier
{
    public function modifyToken($text)
    {
        return mb_strtolower($text, 'utf-8');
    }
}
