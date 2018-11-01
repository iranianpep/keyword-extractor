<?php

namespace KeywordExtractor\Modifiers\Filters;

use KeywordExtractor\Modifiers\AbstractModifier;

class NumberFilter extends AbstractModifier
{
    public function modifyToken($token)
    {
        if (is_numeric($token) === true) {
            return '';
        }

        return $token;
    }
}
