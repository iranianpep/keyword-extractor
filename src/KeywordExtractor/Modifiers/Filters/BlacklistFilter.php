<?php

namespace KeywordExtractor\Modifiers\Filters;

use KeywordExtractor\Modifiers\AbstractModifier;

class BlacklistFilter extends AbstractModifier
{
    private $blacklist;

    public function __construct(array $blacklist)
    {
        $this->setBlacklist($blacklist);
    }

    public function modifyToken($token)
    {
        if (in_array($token, $this->getBlacklist()) === true) {
            return '';
        }

        return $token;
    }

    /**
     * @return array
     */
    public function getBlacklist(): array
    {
        return $this->blacklist;
    }

    /**
     * @param array $blacklist
     */
    public function setBlacklist(array $blacklist): void
    {
        $this->blacklist = $blacklist;
    }
}
