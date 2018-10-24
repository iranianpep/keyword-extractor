<?php

namespace KeywordExtractor\Modifiers\Filters;

class PunctuationFilter extends AbstractFilter
{
    private $punctuations;

    public function __construct($punctuations = [])
    {
        $this->setPunctuations($punctuations);
    }

    public function modifyToken($token)
    {
        return rtrim($token, " \t\n\r\0\x0B".implode('', $this->getPunctuations()));
    }

    /**
     * @return array
     */
    public function getPunctuations()
    {
        if (empty($this->punctuations)) {
            return $this->getDefaultPunctuations();
        }

        return $this->punctuations;
    }

    /**
     * @param array $punctuations
     */
    public function setPunctuations(array $punctuations): void
    {
        $this->punctuations = $punctuations;
    }

    private function getDefaultPunctuations()
    {
        return [
            '!', '$', '%', '&', '(', ')', '*', "'", ',',
            '\\', '-', '.', '/', ':', ';', '<', '=', '>', '?', '@',
            '^', '_', '`', '{', '|', '}', '~', '[', ']',
        ];
    }
}
