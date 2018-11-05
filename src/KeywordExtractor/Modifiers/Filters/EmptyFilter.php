<?php

namespace KeywordExtractor\Modifiers\Filters;

use KeywordExtractor\Modifiers\AbstractModifier;

class EmptyFilter extends AbstractModifier
{
    public function modifyToken($token)
    {
        if ($token === '' || ctype_space($token)) {
            return '';
        }

        return $token;
    }

    public function modifyTokens(array $array): array
    {
        foreach ($array as $key => $value) {
            if ($this->modifyToken($value) === '') {
                unset($array[$key]);
            }
        }

        return $array;
    }
}
