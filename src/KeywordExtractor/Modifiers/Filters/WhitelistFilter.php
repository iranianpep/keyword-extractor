<?php

namespace KeywordExtractor\Modifiers\Filters;

class WhitelistFilter extends AbstractFilter
{
    private $whitelist;

    public function __construct(array $whitelist)
    {
        $this->setWhitelist($whitelist);
    }

    public function modifyToken($token)
    {
        if (in_array($token, $this->getWhitelist())) {
            return $token;
        }

        return '';
    }

    /**
     * @return array
     */
    public function getWhitelist(): array
    {
        return $this->whitelist;
    }

    /**
     * @param array $whitelist
     */
    public function setWhitelist(array $whitelist): void
    {
        $this->whitelist = $whitelist;
    }
}
