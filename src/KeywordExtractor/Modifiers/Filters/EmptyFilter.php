<?php

namespace KeywordExtractor\Modifiers\Filters;

class EmptyFilter extends AbstractFilter
{
    public function modifyArray(array $array)
    {
        foreach ($array as $key => $word) {
            $word = $this->modifyText($word);

            if ($word === '') {
                unset($array[$key]);
            }
        }

        return array_values($array);
    }

    public function modifyText($text)
    {
        if ($text === '' || ctype_space($text)) {
            return '';
        }

        return $text;
    }
}
