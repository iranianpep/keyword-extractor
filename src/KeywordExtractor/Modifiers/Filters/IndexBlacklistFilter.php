<?php

namespace KeywordExtractor\Modifiers\Filters;

use KeywordExtractor\Modifiers\AbstractModifier;

class IndexBlacklistFilter extends AbstractModifier
{
    private $indexBlacklist;

    public function __construct(array $indexBlacklist)
    {
        $this->setIndexBlacklist($indexBlacklist);
    }

    public function modifyToken($token)
    {
        return $token;
    }

    public function modifyTokens(array $array): array
    {
        foreach ($this->getIndexBlacklist() as $index) {
            unset($array[$index]);
        }

        return $array;
    }

    /**
     * @return array
     */
    public function getIndexBlacklist(): array
    {
        return $this->indexBlacklist;
    }

    /**
     * @param array $indexBlacklist
     */
    public function setIndexBlacklist(array $indexBlacklist): void
    {
        $this->indexBlacklist = $indexBlacklist;
    }
}
