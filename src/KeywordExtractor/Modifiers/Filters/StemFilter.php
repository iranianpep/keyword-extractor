<?php

namespace KeywordExtractor\Modifiers\Filters;

use KeywordExtractor\Modifiers\AbstractModifier;
use NlpTools\Stemmers\PorterStemmer;

class StemFilter extends AbstractModifier
{
    public function modifyToken($token)
    {
        $stemmer = new PorterStemmer();

        return $stemmer->stem($token);
    }
}
