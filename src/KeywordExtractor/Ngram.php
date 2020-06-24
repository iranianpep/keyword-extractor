<?php

namespace KeywordExtractor;

/**
 * Class Ngram
 */
class Ngram
{
    /**
     * @var String $word
     */
    private $word;

    /**
     * @var array $indexes
     */
    private $indexes;

    public function __construct(string $word, array $indexes)
    {
        $this->setWord($word);
        $this->setIndexes($indexes);
    }

    /**
     * @return String
     */
    public function getWord(): string
    {
        return $this->word;
    }

    /**
     * @param String $word
     */
    public function setWord(string $word): void
    {
        $this->word = $word;
    }

    /**
     * @return array
     */
    public function getIndexes(): array
    {
        return $this->indexes;
    }

    /**
     * @param array $indexes
     */
    public function setIndexes(array $indexes): void
    {
        $this->indexes = $indexes;
    }
}
