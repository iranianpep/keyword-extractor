<?php

namespace KeywordExtractor;

use KeywordExtractor\Modifiers\ModifierInterface;
use NlpTools\Stemmers\PorterStemmer;
use NlpTools\Tokenizers\WhitespaceTokenizer;

class KeywordExtractor
{
    private $blacklist;
    private $whitelist;
    private $stopWords;
    private $filter;
    private $modifiers;
    private $keywords;

    /**
     * order is important.
     */
    const NGRAM_SIZES = [3, 2];

    const INDEXES_KEY = 'indexes';
    const WORD_KEY = 'word';

    /**
     * Generate n-grams
     * Credits to https://github.com/yooper/php-text-analysis/blob/master/src/NGrams/NGramFactory.php.
     *
     * @param array $tokens
     * @param       $ngramSize
     *
     * @return array
     */
    private function generateNgrams(array $tokens, $ngramSize): array
    {
        $length = count($tokens) - $ngramSize + 1;

        if ($length < 1) {
            return [];
        }

        $ngrams = [];
        for ($i = 0; $i < $length; $i++) {
            $ngrams[] = $this->extractNgram($tokens, $ngramSize, $i);
        }

        return $ngrams;
    }

    /**
     * @param array $tokens
     * @param       $ngramSize
     * @param       $currentIndex
     *
     * @return array
     */
    private function extractNgram(array $tokens, $ngramSize, $currentIndex): array
    {
        $word = '';
        $subIndexes = [];
        for ($j = 0; $j < $ngramSize; $j++) {
            $subIndex = $currentIndex + $j;
            $subIndexes[] = $subIndex;

            if (isset($tokens[$subIndex])) {
                $word .= $tokens[$subIndex];
            }

            if ($j < $ngramSize - 1) {
                $word .= ' ';
            }
        }

        return [self::WORD_KEY => $word, self::INDEXES_KEY => $subIndexes];
    }

    /**
     * @param $word
     *
     * @return bool
     */
    private function isStopWord($word): bool
    {
        return in_array($word, $this->getStopWords());
    }

    /**
     * @param $word
     *
     * @return bool
     */
    private function isWhitelisted($word): bool
    {
        return in_array($word, $this->getWhitelist());
    }

    /**
     * @param $word
     *
     * @return bool
     */
    private function isBlackListed($word): bool
    {
        return in_array($word, $this->getBlacklist());
    }

    /**
     * @param $text
     *
     * @return array
     */
    public function run($text): array
    {
        // reset the keywords
        $this->keywords = [];

        $text = mb_strtolower($text, 'utf-8');
        $text = $this->getFilter()->removeEmails($text);
        $words = (new WhitespaceTokenizer())->tokenize($text);
        $words = $this->getFilter()->removeRightPunctuations($words, $this->getWhitelist());

        /**
         * get rid of empty elements in the array
         * it happens when there is a punctuation after an email, and email and then punctuation get deleted.
         */
        $words = $this->getFilter()->removeEmptyArrayElements($words);


        //$words = $this->processNgrams($words);

        $stemmer = new PorterStemmer();
        foreach ([3, 2, 1] as $ngramSize) {
            foreach ($this->generateNgrams($words, $ngramSize) as $wordAndIndexes) {
                $word = $wordAndIndexes[self::WORD_KEY];
                if ($ngramSize === 1) {
                    if (is_numeric($word)) {
                        continue;
                    }

                    if ($this->isWhitelisted($word) === true) {
                        $this->addKeyword($word);
                    } elseif ($this->isStopWord($word) === false && $this->isBlackListed($word) === false) {
                        $stemmedWord = $stemmer->stem($word);

                        if ($this->isBlackListed($stemmedWord) === true) {
                            continue;
                        }

                        $this->addKeyword($stemmer->stem($word));
                    }
                } else {
                    if ($this->isWhitelisted($word) === true) {
                        // can be added
                        $this->addKeyword($word);
                        $words = $this->getFilter()->removeWordsByIndexes($words, $wordAndIndexes[self::INDEXES_KEY]);
                    } elseif ($this->isBlackListed($word) === true) {
                        $words = $this->getFilter()->removeWordsByIndexes($words, $wordAndIndexes[self::INDEXES_KEY]);
                    }
                }
            }
        }

        //$words = $this->getFilter()->removeNumbers($words);

        return $this->getKeywords();
        //return $this->extractKeywordsFromWords($words);
    }

    /**
     * @param $words
     *
     * @return array
     */
    private function processNgrams($words): array
    {
        foreach (self::NGRAM_SIZES as $ngramSize) {
            $words = $this->processNgram($words, $ngramSize);
        }

        return $words;
    }

    /**
     * @param array $words
     * @param       $ngramSize
     *
     * @return array
     */
    private function processNgram(array $words, $ngramSize)
    {
        foreach ($this->generateNgrams($words, $ngramSize) as $wordAndIndexes) {
            if ($this->isWhitelisted($wordAndIndexes[self::WORD_KEY]) === true) {
                // can be added
                $this->addKeyword($wordAndIndexes[self::WORD_KEY]);
                $words = $this->getFilter()->removeWordsByIndexes($words, $wordAndIndexes[self::INDEXES_KEY]);
            } elseif ($this->isBlackListed($wordAndIndexes[self::WORD_KEY]) === true) {
                $words = $this->getFilter()->removeWordsByIndexes($words, $wordAndIndexes[self::INDEXES_KEY]);
            }
        }

        return $words;
    }

    /**
     * @param       $words
     *
     * @return array
     */
    private function extractKeywordsFromWords($words): array
    {
        $stemmer = new PorterStemmer();
        foreach ($words as $word) {
            if ($this->isWhitelisted($word) === true) {
                $this->addKeyword($word);
            } elseif ($this->isStopWord($word) === false && $this->isBlackListed($word) === false) {
                $stemmedWord = $stemmer->stem($word);

                if ($this->isBlackListed($stemmedWord) === true) {
                    continue;
                }

                $this->addKeyword($stemmer->stem($word));
            }
        }

        return $this->getKeywords();
    }

    /**
     * @return array
     */
    public function getBlacklist(): array
    {
        if (!isset($this->blacklist)) {
            return [];
        }

        return $this->blacklist;
    }

    /**
     * @param array $blacklist
     */
    public function setBlacklist(array $blacklist): void
    {
        $this->blacklist = $blacklist;
    }

    /**
     * @return array
     */
    public function getWhitelist(): array
    {
        if (!isset($this->whitelist)) {
            return [];
        }

        return $this->whitelist;
    }

    /**
     * @param array $whitelist
     */
    public function setWhitelist(array $whitelist): void
    {
        $this->whitelist = $whitelist;
    }

    /**
     * @return array|null
     */
    public function getStopWords():? array
    {
        if (!isset($this->stopWords)) {
            $stopWordsPath = __DIR__.DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'stopwords-en.json';
            $content = json_decode(file_get_contents($stopWordsPath), true);

            $this->setStopWords($content);
        }

        return $this->stopWords;
    }

    /**
     * @param array $stopWords
     */
    public function setStopWords(array $stopWords): void
    {
        $this->stopWords = $stopWords;
    }

    /**
     * @return Filter|null
     */
    public function getFilter():? Filter
    {
        if (!isset($this->filter)) {
            $this->setFilter(new Filter());
        }

        return $this->filter;
    }

    /**
     * @param Filter $filter
     */
    public function setFilter(Filter $filter): void
    {
        $this->filter = $filter;
    }

    /**
     * @return array|null
     */
    public function getModifiers():? array
    {
        return $this->modifiers;
    }

    /**
     * @param array $modifiers
     */
    public function setModifiers(array $modifiers): void
    {
        $this->modifiers = $modifiers;
    }

    /**
     * @param ModifierInterface $modifier
     */
    public function addModifier(ModifierInterface $modifier): void
    {
        $existingModifiers = $this->getModifiers();
        $existingModifiers[] = $modifier;

        $this->setModifiers($existingModifiers);
    }

    /**
     * @return array|null
     */
    private function getKeywords():? array
    {
        return $this->keywords;
    }

    /**
     * @param $keyword
     */
    public function addKeyword($keyword): void
    {
        $this->keywords[] = $keyword;
    }
}
