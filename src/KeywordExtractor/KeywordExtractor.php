<?php

namespace KeywordExtractor;

use KeywordExtractor\Modifiers\Filters\BlacklistFilter;
use KeywordExtractor\Modifiers\Filters\EmailFilter;
use KeywordExtractor\Modifiers\Filters\EmptyFilter;
use KeywordExtractor\Modifiers\Filters\PunctuationFilter;
use KeywordExtractor\Modifiers\Filters\WhitelistFilter;
use KeywordExtractor\Modifiers\ModifierInterface;
use KeywordExtractor\Modifiers\Transformers\LowerCaseTransformer;
use KeywordExtractor\Modifiers\Transformers\TokenTransformer;
use NlpTools\Stemmers\PorterStemmer;

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

    private function getDefaultModifiers()
    {
        return [
            new LowerCaseTransformer(),
            new EmailFilter(),
            new TokenTransformer(),
            new PunctuationFilter(),
            new EmptyFilter(),
            new BlacklistFilter($this->getBlacklist())
        ];
    }

    /**
     * @param $input
     *
     * @return array
     */
    public function run($input): array
    {
        // reset the keywords
        $this->keywords = [];

        // lowercase and tokenize
        $input = (new LowerCaseTransformer())->modify($input);
        $input = (new TokenTransformer())->modify($input);



        // generate data sets based on n-grams

        // for each data set go through the modifiers starting from the biggest n-gram


//        foreach ($this->getDefaultModifiers() as $modifier) {
//            if (!$modifier instanceof ModifierInterface) {
//                continue;
//            }
//
//            $input = $modifier->modify($input);
//        }

        $stemmer = new PorterStemmer();
        // n grams can be passed as an arg to the constructor
        foreach ([3, 2, 1] as $ngramSize) {
            foreach ($this->generateNgrams($input, $ngramSize) as $key => $wordAndIndexes) {

                $word = $wordAndIndexes[self::WORD_KEY];

                foreach ($this->getDefaultModifiers() as $modifier) {
                    if (!$modifier instanceof ModifierInterface) {
                        continue;
                    }

                    // TODO skip if $ngramSize > 1 and something else than BlacklistFilter or WhitelistFilter

                    $word = $modifier->modify($word);

                    if ($ngramSize > 1 && ($modifier instanceof BlacklistFilter || $modifier instanceof WhitelistFilter)) {
                        if ($modifier instanceof WhitelistFilter && !empty($word)) {
                            $this->addKeyword($word);

                            // since it's whitelisted, ignore other modifiers
                            break;
                        } elseif ($modifier instanceof BlacklistFilter && empty($word)) {
                            $input = $this->getFilter()->removeWordsByIndexes($input, $wordAndIndexes[self::INDEXES_KEY]);
                        }
                    }
                }


                // if the word survives after applying all the filters it's deserved to be added to the keywords!
                if ($ngramSize === 1 && !empty($word)) {
                    $this->addKeyword($word);
                }
                // if $ngramSize > 1 it can only have whitelist and blacklist modifier
                // if is whitelisted
                // ... add to keyword list
                // ... remove it from $input


                // if is blacklisted
                // ... remove it from $input

                // if $ngramSize === 1, apply the rest of modifiers too


//                if ($ngramSize === 1) {
//                    if (is_numeric($word)) {
//                        continue;
//                    }
//
//                    if ($this->isWhitelisted($word) === true) {
//                        $this->addKeyword($word);
//                    } elseif ($this->isStopWord($word) === false && $this->isBlackListed($word) === false) {
//                        $stemmedWord = $stemmer->stem($word);
//
//                        if ($this->isBlackListed($stemmedWord) === true) {
//                            continue;
//                        }
//
//                        $this->addKeyword($stemmedWord);
//                    }
//                } else {
//                    if ($this->isWhitelisted($word) === true) {
//                        // can be added
//                        $this->addKeyword($word);
//                        $input = $this->getFilter()->removeWordsByIndexes($input, $wordAndIndexes[self::INDEXES_KEY]);
//                    } elseif ($this->isBlackListed($word) === true) {
//                        $input = $this->getFilter()->removeWordsByIndexes($input, $wordAndIndexes[self::INDEXES_KEY]);
//                    }
//                }
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
