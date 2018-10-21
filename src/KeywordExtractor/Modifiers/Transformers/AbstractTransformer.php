<?php

namespace KeywordExtractor\Modifiers\Transformers;

use KeywordExtractor\Modifiers\ModifierInterface;

abstract class AbstractTransformer implements ModifierInterface
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
