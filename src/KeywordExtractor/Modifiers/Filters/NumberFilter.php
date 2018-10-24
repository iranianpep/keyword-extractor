<?php

namespace KeywordExtractor\Modifiers\Filters;

class NumberFilter extends AbstractFilter
{
    public function modifyToken($token)
    {
        if (is_numeric($token) === true) {
            return '';
        }

        return $token;
    }
}
