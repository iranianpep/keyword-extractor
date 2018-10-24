<?php

namespace KeywordExtractor\Modifiers\Filters;

class EmptyFilter extends AbstractFilter
{
    public function modifyToken($token)
    {
        if ($token === '' || ctype_space($token)) {
            return '';
        }

        return $token;
    }
}
