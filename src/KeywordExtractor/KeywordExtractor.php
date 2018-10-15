<?php

namespace KeywordExtractor;

use NlpTools\Stemmers\PorterStemmer;
use NlpTools\Tokenizers\WhitespaceTokenizer;

class KeywordExtractor
{
    private $blacklist;
    private $whitelist;
    private $stopWords;

    /**
     * order is important
     */
    const NGRAM_SIZES = [3, 2];

    /**
     * Generate n-grams
     * Credits to https://github.com/yooper/php-text-analysis/blob/master/src/NGrams/NGramFactory.php
     *
     * @param array  $tokens
     * @param        $ngramSize
     * @param string $separator
     *
     * @return array
     */
    private function generateNgrams(array $tokens, $ngramSize, $separator = ' '): array
    {
        $separatorLength = strlen($separator);
        $length = count($tokens) - $ngramSize + 1;

        if ($length < 1) {
            return [];
        }

        $ngrams = [];
        for ($i = 0; $i < $length; $i++) {
            // reset token and sub indexes
            $word = '';
            $subIndexes = [];
            for ($j = 0; $j < $ngramSize; $j++) {
                $subIndex = $i + $j;
                $subIndexes[] = $subIndex;
                $word .= $tokens[$subIndex];

                if ($j < $ngramSize - $separatorLength) {
                    $word .= $separator;
                }
            }

            $ngrams[] = ['word' => $word, 'indexes' => $subIndexes];
        }

        return $ngrams;
    }

    private function removePunctuation($word): string
    {
        $searchFor = [
            '!','#','$','%','&','(',')','*','+',"'",',',
            '\\','-','.','/',':',';','<','=','>','?','@',
            '^','_','`','{','|','}','~','[',']'
        ];

        return trim($word, " \t\n\r\0\x0B" . implode('', $searchFor));
    }

    private function removePunctuations($words)
    {
        foreach ($words as $key => $word) {
            $words[$key] = $this->removePunctuation($word);
        }

        return $words;
    }

    private function isStopWord($word): bool
    {
        return in_array($word, $this->getStopWords());
    }
    
    private function isWhitelisted($word): bool
    {
        return in_array($word, $this->getWhitelist());
    }

    private function isBlackListed($word): bool
    {
        return in_array($word, $this->getBlacklist());
    }

    private function filterWordsByIndexes($words, $indexes)
    {
        foreach ($indexes as $index) {
            unset($words[$index]);
        }

        return $words;
    }

    public function run($text)
    {
        $text = mb_strtolower($text, 'utf-8');
        $words = (new WhitespaceTokenizer())->tokenize($text);
        $words = $this->removePunctuations($words);
        $result = $this->processNgrams($words);

        return $this->extractKeywordsFromWords($result['words'], $result['keywords']);
    }

    private function processNgrams($words)
    {
        $keywords = [];
        foreach (self::NGRAM_SIZES as $ngramSize) {
            $triNgrams = $this->generateNgrams($words, $ngramSize);

            foreach ($triNgrams as $wordAndIndexes) {
                $word = $wordAndIndexes['word'];

                if ($this->isWhitelisted($word) === true) {
                    // can be added
                    $keywords[] = $word;
                    $words = $this->filterWordsByIndexes($words, $wordAndIndexes['indexes']);
                } elseif ($this->isBlackListed($word) === true) {
                    // should be removed from the original
                    $words = $this->filterWordsByIndexes($words, $wordAndIndexes['indexes']);
                }
            }
        }

        return ['words' => $words, 'keywords' => $keywords];
    }

    private function extractKeywordsFromWords($words, $existingKeywords = [])
    {
        $stemmer = new PorterStemmer();
        foreach ($words as $word) {
            if ($this->isWhitelisted($word) === true) {
                $existingKeywords[] = $word;
                continue;
            }

            // remove stop words AND blocked ones
            if ($this->isStopWord($word) === true || $this->isBlackListed($word) === true) {
                continue;
            }

            $existingKeywords[] = $stemmer->stem($word);
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
            $content = json_decode(file_get_contents($stopWordsPath), true) ;

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
}
