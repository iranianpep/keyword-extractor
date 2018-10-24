<?php

namespace KeywordExtractor\Modifiers\Filters;

use KeywordExtractor\Modifiers\ModifierInterface;

abstract class AbstractFilter implements ModifierInterface
{
    public function modify($input)
    {
        if (is_array($input) === true) {
            return $this->modifyTokens($input);
        } else {
            return $this->modifyToken($input);
        }
    }

    public function modifyTokens(array $array)
    {
        foreach ($array as $key => $value) {
            $array[$key] = $this->modifyToken($value);

            if ($array[$key] === '') {
                unset($array[$key]);
            }
        }

        return array_values($array);
    }
}
