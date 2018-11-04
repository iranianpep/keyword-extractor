<?php

namespace KeywordExtractor;

use KeywordExtractor\Modifiers\Filters\BlacklistFilter;
use KeywordExtractor\Modifiers\Filters\EmailFilter;
use KeywordExtractor\Modifiers\Filters\IndexBlacklistFilter;
use KeywordExtractor\Modifiers\Filters\NumberFilter;
use KeywordExtractor\Modifiers\Filters\PunctuationFilter;
use KeywordExtractor\Modifiers\Filters\StemFilter;
use KeywordExtractor\Modifiers\Filters\StopWordFilter;
use KeywordExtractor\Modifiers\Filters\WhitelistFilter;
use KeywordExtractor\Modifiers\ModifierInterface;
use KeywordExtractor\Modifiers\Transformers\LowerCaseTransformer;
use KeywordExtractor\Modifiers\Transformers\TokenTransformer;

class KeywordExtractor
{
    private $blacklist;
    private $whitelist;
    private $modifiers;
    private $keywords;

    /**
     * order is important.
     */
    const NGRAM_SIZES = [3, 2, 1];

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
     * @return array
     */
    private function getDefaultModifiers(): array
    {
        return [
            new EmailFilter(),
            new PunctuationFilter(),
            new WhitelistFilter($this->getWhitelist()),
            new BlacklistFilter($this->getBlacklist()),
            new StopWordFilter(),
            new NumberFilter(),
            new StemFilter(),
            // run the blacklist even after stemming too
            new BlacklistFilter($this->getBlacklist()),
        ];
    }

    /**
     * @param $string
     *
     * @return array
     */
    public function run(string $string): array
    {
        // reset the keywords
        $this->setKeywords([]);

        // lowercase and tokenize
        $string = (new LowerCaseTransformer())->modify($string);
        $tokens = (new TokenTransformer())->modify($string);

        // n grams can be passed as an arg to the constructor
        foreach (self::NGRAM_SIZES as $ngramSize) {
            $tokens = $this->extractNgramKeywords($tokens, $ngramSize);
        }

        return $this->getKeywords();
    }

    /**
     * @param array $tokens
     * @param int   $ngramSize
     *
     * @return array
     */
    private function extractNgramKeywords(array $tokens, int $ngramSize): array
    {
        foreach ($this->generateNgrams($tokens, $ngramSize) as $wordAndIndexes) {
            $word = $wordAndIndexes[self::WORD_KEY];
            $original = $word;

            $indexes = $wordAndIndexes[self::INDEXES_KEY];

            $result = $this->applyModifiers($tokens, $word, $indexes);

            $tokens = $result['tokens'];
            $word = $result['word'];
            $alreadyAdded = $result['alreadyAdded'];

            // if the word survives after applying all the filters it's deserved to be added to the keywords!
            if ($ngramSize === 1 && !empty($word) && $alreadyAdded === false) {
                $this->addKeyword($word, $original);
            }
        }

        return $tokens;
    }

    /**
     * @param $tokens
     * @param $word
     * @param $indexes
     *
     * @return array
     */
    private function applyModifiers(array $tokens, string $word, array $indexes): array
    {
        $alreadyAdded = false;

        /**
         * @var ModifierInterface
         */
        foreach ($this->getModifiers() as $modifier) {
            $toBeModified = $word;
            $word = $modifier->modify($word);

            if ($modifier instanceof WhitelistFilter) {
                if (!empty($word) === true) {
                    // word is whitelisted
                    $this->addKeyword($word, $toBeModified);

                    $tokens = (new IndexBlacklistFilter($indexes))->modifyTokens($tokens);
                    $alreadyAdded = true;
                    break;
                }

                // word is NOT whitelisted - reset the empty word to the state before applying the whitelist
                $word = $toBeModified;
            }

            if ($modifier instanceof BlacklistFilter && empty($word)) {
                $tokens = (new IndexBlacklistFilter($indexes))->modifyTokens($tokens);

                // since it's blacklisted, ignore other modifiers
                break;
            }
        }

        return [
            'tokens'       => $tokens,
            'word'         => $word,
            'alreadyAdded' => $alreadyAdded,
        ];
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
     * @return array
     */
    public function getModifiers(): array
    {
        if (!isset($this->modifiers)) {
            return $this->getDefaultModifiers();
        }

        return $this->modifiers;
    }

    /**
     * @param ModifierInterface $modifier
     */
    public function addModifier(ModifierInterface $modifier): void
    {
        $this->modifiers[] = $modifier;
    }

    /**
     * @param array $modifiers
     */
    public function setModifiers(array $modifiers): void
    {
        $this->modifiers = [];
        foreach ($modifiers as $modifier) {
            $this->addModifier($modifier);
        }
    }

    /**
     * @return array
     */
    private function getKeywords(): array
    {
        if (empty($this->keywords)) {
            return [];
        }

        return $this->keywords;
    }

    /**
     * @param $keyword
     * @param $original
     */
    public function addKeyword($keyword, $original): void
    {
        $frequency = 1;
        $originals = [];
        if ($this->keywordExists($keyword) === true) {
            $frequency = $this->keywords[$keyword]['frequency'] + 1;
            $originals = $this->keywords[$keyword]['originals'];
        }

        if (in_array($original, $originals) === false) {
            $originals[] = $original;
        }

        $this->keywords[$keyword] = [
            'frequency' => $frequency,
            'originals' => $originals,
        ];
    }

    /**
     * @param $keyword
     *
     * @return bool
     */
    private function keywordExists($keyword): bool
    {
        return array_key_exists($keyword, $this->getKeywords());
    }

    /**
     * @param array $keywords
     */
    public function setKeywords(array $keywords)
    {
        $this->keywords = $keywords;
    }
}
