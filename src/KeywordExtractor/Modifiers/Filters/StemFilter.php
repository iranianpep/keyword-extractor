<?php

namespace KeywordExtractor\Modifiers\Filters;

use NlpTools\Stemmers\PorterStemmer;

class StemFilter extends AbstractFilter
{
    public function modifyToken($token)
    {
        $stemmer = new PorterStemmer();
        return $stemmer->stem($token);
    }
}
