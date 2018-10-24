<?php

namespace KeywordExtractor\Modifiers\Filters;

class BlacklistFilter extends AbstractFilter
{
    private $blacklist;

    public function __construct(array $blacklist)
    {
        $this->setBlacklist($blacklist);
    }

    public function modifyToken($token)
    {
        if (in_array($token, $this->getBlacklist())) {
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
