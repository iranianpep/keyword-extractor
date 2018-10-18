<?php

namespace KeywordExtractor;

use NlpTools\Stemmers\PorterStemmer;
use NlpTools\Tokenizers\WhitespaceTokenizer;

class KeywordExtractor
{
    private $blacklist;
    private $whitelist;
    private $stopWords;
    private $filter;

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
<<<<<<< HEAD
=======
     * @return string
     */
    private function removePunctuation($word): string
    {
        $searchFor = [
            '!', '#', '$', '%', '&', '(', ')', '*', '+', "'", ',',
            '\\', '-', '.', '/', ':', ';', '<', '=', '>', '?', '@',
            '^', '_', '`', '{', '|', '}', '~', '[', ']',
        ];

        if (in_array($word, $searchFor) === true) {
            return '';
        }

        return trim($word, " \t\n\r\0\x0B".implode('', $searchFor));
    }

    /**
     * @param $words
     *
     * @return mixed
     */
    private function removePunctuations($words): array
    {
        foreach ($words as $key => $word) {
            $words[$key] = $this->removePunctuation($word);
        }

        return $words;
    }

    /**
     * @param $words
     *
     * @return array
     */
    private function removeNumbers($words): array
    {
        foreach ($words as $key => $word) {
            if (is_numeric($word) === true) {
                unset($words[$key]);
            }
        }

        return $words;
    }

    /**
     * @param $word
     *
>>>>>>> 4fca0c12d5c4c5acd3a1823b4a6c84240f8fece4
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
        $text = mb_strtolower($text, 'utf-8');
        $text = $this->removeEmails($text);
        $words = (new WhitespaceTokenizer())->tokenize($text);
<<<<<<< HEAD
        $words = $this->getFilter()->removePunctuations($words);
=======
        $words = $this->removePunctuations($words);

        /**
         * get rid of empty elements in the array
         * it happens when there is a punctuation after an email, and email and then punctuation get deleted.
         */
        $words = $this->removeEmptyArrayElements($words);
>>>>>>> 4fca0c12d5c4c5acd3a1823b4a6c84240f8fece4
        $result = $this->processNgrams($words);
        $words = $this->getFilter()->removeNumbers($result['words']);

        return $this->extractKeywordsFromWords($words, $result['keywords']);
    }

    /**
     * @param $words
     *
     * @return array
     */
    private function processNgrams($words): array
    {
        $result = [];
        foreach (self::NGRAM_SIZES as $ngramSize) {
            $keywords = isset($result['keywords']) ? $result['keywords'] : [];
            $words = isset($result['words']) ? $result['words'] : $words;
            $result = array_merge($result, $this->processNgram($words, $ngramSize, $keywords));
        }

        return $result;
    }

    /**
     * @param array $words
     * @param       $ngramSize
     * @param array $keywords
     *
     * @return array
     */
    private function processNgram(array $words, $ngramSize, array $keywords)
    {
        foreach ($this->generateNgrams($words, $ngramSize) as $wordAndIndexes) {
            if ($this->isWhitelisted($wordAndIndexes[self::WORD_KEY]) === true) {
                // can be added
                $keywords[] = $wordAndIndexes[self::WORD_KEY];
                $words = $this->getFilter()->removeWordsByIndexes($words, $wordAndIndexes[self::INDEXES_KEY]);
            } elseif ($this->isBlackListed($wordAndIndexes[self::WORD_KEY]) === true) {
                $words = $this->getFilter()->removeWordsByIndexes($words, $wordAndIndexes[self::INDEXES_KEY]);
            }
        }

        return ['words' => $words, 'keywords' => $keywords];
    }

    /**
     * @param       $words
     * @param array $existingKeywords
     *
     * @return array
     */
    private function extractKeywordsFromWords($words, $existingKeywords = []): array
    {
        $stemmer = new PorterStemmer();
        foreach ($words as $word) {
            if ($this->isWhitelisted($word) === true) {
                $existingKeywords[] = $word;
            } elseif ($this->isStopWord($word) === false && $this->isBlackListed($word) === false) {
                $existingKeywords[] = $stemmer->stem($word);
            }
        }

        return $existingKeywords;
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
<<<<<<< HEAD
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
=======
     * @param $string
     *
     * @return null|string
     */
    private function removeEmails($string):? string
    {
        $pattern = '/(?:[A-Za-z0-9!#$%&\'*+=?^_`{|}~-]+(?:\.[A-Za-z0-9!#$%&\'*+=?^_`{|}~-]+)*|\"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\")@(?:(?:[A-Za-z0-9](?:[A-Za-z0-9-]*[A-Za-z0-9])?\.)+[A-Za-z0-9](?:[A-Za-z0-9-]*[A-Za-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[A-Za-z0-9-]*[A-Za-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/';

        return preg_replace($pattern, '', $string);
    }

    /**
     * @param array $words
     *
     * @return array
     */
    private function removeEmptyArrayElements(array $words): array
    {
        return array_filter($words, 'strlen');
>>>>>>> 4fca0c12d5c4c5acd3a1823b4a6c84240f8fece4
    }
}
