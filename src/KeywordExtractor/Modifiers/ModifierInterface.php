<?php

namespace KeywordExtractor\Modifiers;

interface ModifierInterface
{
    /**
     * @param $input
     *
     * @return mixed
     */
    public function modify($input);

    /**
     * @param $token
     *
     * @return mixed
     */
    public function modifyToken($token);

    /**
     * @param array $tokens
     *
     * @return array
     */
    public function modifyTokens(array $tokens): array;
}
