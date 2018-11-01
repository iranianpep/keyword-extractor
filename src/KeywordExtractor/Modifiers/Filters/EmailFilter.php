<?php

namespace KeywordExtractor\Modifiers\Filters;

use KeywordExtractor\Modifiers\AbstractModifier;

class EmailFilter extends AbstractModifier
{
    private $pattern;

    public function __construct($pattern = null)
    {
        if (empty($pattern)) {
            $pattern = $this->getDefaultPattern();
        }

        $this->setPattern($pattern);
    }

    public function modifyToken($token)
    {
        return preg_replace($this->getPattern(), '', $token);
    }

    /**
     * @return string
     */
    public function getPattern(): string
    {
        return $this->pattern;
    }

    /**
     * @param string $pattern
     */
    public function setPattern(string $pattern): void
    {
        $this->pattern = $pattern;
    }

    private function getDefaultPattern()
    {
        // break the pattern to avoid exceeding the line
        $pattern = '/(?:[A-Za-z0-9!#$%&\'*+=?^_`{|}~-]+(?:\.[A-Za-z0-9!#$%&\'*+=?^_`{|}~-]+)*';
        $pattern .= '|\"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]';
        $pattern .= '|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\")@(?:(?:[A-Za-z0-9]';
        $pattern .= '(?:[A-Za-z0-9-]*[A-Za-z0-9])?\.)+[A-Za-z0-9](?:[A-Za-z0-9-]*[A-Za-z0-9])?';
        $pattern .= '|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}';
        $pattern .= '(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?';
        $pattern .= '|[A-Za-z0-9-]*[A-Za-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]';
        $pattern .= '|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/';

        return $pattern;
    }
}
