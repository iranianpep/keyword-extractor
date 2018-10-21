<?php

namespace KeywordExtractor\Modifiers\Filters;

use KeywordExtractor\Modifiers\ModifierInterface;

abstract class AbstractFilter implements ModifierInterface
{
    public function modify($input)
    {
        if (is_array($input) === true) {
            return $this->modifyArray($input);
        } else {
            return $this->modifyText($input);
        }
    }

    public function modifyArray(array $array)
    {
        foreach ($array as $key => $value) {
            $array[$key] = $this->modifyText($value);
        }

        return $array;
    }
}
