<?php

namespace KeywordExtractor\Modifiers\Filters;

class BlacklistFilter extends AbstractFilter
{
    private $blacklist;

    public function __construct(array $blacklist)
    {
        $this->setBlacklist($blacklist);
    }

    public function isInFilter($token): bool
    {
        return in_array($token, $this->getBlacklist());
    }

    public function modifyToken($token)
    {
        if ($this->isInFilter($token) === true) {
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
