<?php

namespace KeywordExtractor\Modifiers;

interface ModifierInterface
{
    public function modify($input);

    public function modifyToken($token);

    public function modifyTokens(array $tokens);
}
