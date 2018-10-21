<?php

namespace KeywordExtractor\Modifiers\Filters;

class NumberFilter extends AbstractFilter
{
    public function modifyText($text)
    {
        if (is_numeric($text) === true) {
            return '';
        }

        return $text;
    }
}
