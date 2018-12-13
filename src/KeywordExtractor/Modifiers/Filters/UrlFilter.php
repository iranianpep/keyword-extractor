<?php

namespace KeywordExtractor\Modifiers\Filters;

use KeywordExtractor\Modifiers\AbstractModifier;

class UrlFilter extends AbstractModifier
{
    public function modifyToken($token)
    {
        if (filter_var($token, FILTER_VALIDATE_URL)) {
            return '';
        }

        return $token;
    }
}
