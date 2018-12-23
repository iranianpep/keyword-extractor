<?php

namespace KeywordExtractor\Modifiers\Filters;

use KeywordExtractor\Modifiers\AbstractModifier;

class SalaryFilter extends AbstractModifier
{
    public function modifyToken($token)
    {
        $regex = '/^\$?[0-9]*[,]*[0-9]*(\.[0-9]*)?(k|(\/)*hr|(\/)*hour|(\/)*ph|(\/)*b|(\/)*m|(\/)*day)*$/';
        if (preg_match($regex, $token)) {
            return '';
        }

        return $token;
    }
}
