<?php

namespace KeywordExtractor\Modifiers\Filters;

use KeywordExtractor\Modifiers\AbstractModifier;

class PunctuationFilter extends AbstractModifier
{
    private $rightPunctuations;
    private $leftPunctuations;

    public function __construct($rightPunctuations = [])
    {
        $this->setRightPunctuations($rightPunctuations);
    }

    public function modifyToken($token)
    {
        $token = rtrim($token, " \t\n\r\0\x0B".implode('', $this->getRightPunctuations()));

        return ltrim($token, implode('', $this->getLeftPunctuations()));
    }

    /**
     * @return array
     */
    public function getRightPunctuations()
    {
        if (empty($this->rightPunctuations)) {
            return $this->getDefaultRightPunctuations();
        }

        return $this->rightPunctuations;
    }

    /**
     * @return array
     */
    public function getLeftPunctuations()
    {
        if (empty($this->leftPunctuations)) {
            return $this->getDefaultLeftPunctuations();
        }

        return $this->leftPunctuations;
    }

    /**
     * @param array $rightPunctuations
     */
    public function setRightPunctuations(array $rightPunctuations): void
    {
        $this->rightPunctuations = $rightPunctuations;
    }

    /**
     * @param array $leftPunctuations
     */
    public function setLeftPunctuations(array $leftPunctuations): void
    {
        $this->leftPunctuations = $leftPunctuations;
    }

    private function getDefaultRightPunctuations()
    {
        return [
            '!', '$', '%', '&', '(', ')', '*', "'", ',',
            '\\', '-', '.', '/', ':', ';', '<', '=', '>', '?', '@',
            '^', '_', '`', '{', '|', '}', '~', '[', ']',
        ];
    }

    private function getDefaultLeftPunctuations()
    {
        return ['(', "'", '`', '"', '“', '‘'];
    }
}
