<?php

namespace KeywordExtractor\Modifiers;

interface ModifierInterface
{
    public function modify($input);
    public function modifyText($text);
    public function modifyArray(array $array);
}
