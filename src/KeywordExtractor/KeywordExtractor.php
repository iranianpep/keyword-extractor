<?php

namespace KeywordExtractor;

use KeywordExtractor\Modifiers\Filters\BlacklistFilter;
use KeywordExtractor\Modifiers\Filters\EmailFilter;
use KeywordExtractor\Modifiers\Filters\IndexBlacklistFilter;
use KeywordExtractor\Modifiers\Filters\NumberFilter;
use KeywordExtractor\Modifiers\Filters\PunctuationFilter;
use KeywordExtractor\Modifiers\Filters\SalaryFilter;
use KeywordExtractor\Modifiers\Filters\StemFilter;
use KeywordExtractor\Modifiers\Filters\StopWordFilter;
use KeywordExtractor\Modifiers\Filters\UrlFilter;
use KeywordExtractor\Modifiers\Filters\WhitelistFilter;
use KeywordExtractor\Modifiers\ModifierInterface;
use KeywordExtractor\Modifiers\Transformers\LowerCaseTransformer;
use KeywordExtractor\Modifiers\Transformers\TokenTransformer;

/**
 * Class KeywordExtractor.
 */
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

    /**
     * @return array
     */
    private function getDefaultModifiers(): array
    {
        return [
            new UrlFilter(),
            new EmailFilter(),
            new PunctuationFilter(),
            new WhitelistFilter($this->getWhitelist()),
            new BlacklistFilter($this->getBlacklist()),
            new StopWordFilter(),
            new NumberFilter(),
            new SalaryFilter(),
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
        /**
         * @var Ngram $ngram
         */
        foreach ((new NgramHandler())->generateNgrams($tokens, $ngramSize) as $ngram) {
            $result = $this->applyModifiers($tokens, $ngram->getWord(), $ngram->getIndexes());

            $tokens = $result['tokens'];
            $modifiedWord = $result['word'];
            $alreadyAdded = $result['alreadyAdded'];

            // if the word survives after applying all the filters it's deserved to be added to the keywords!
            if ($ngramSize === 1 && !empty($modifiedWord) && $alreadyAdded === false) {
                $this->addKeyword($modifiedWord, $ngram->getWord());
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
        $original = $word;

        /*
         * @var ModifierInterface
         */
        foreach ($this->getModifiers() as $modifier) {
            $toBeModified = $word;
            $word = $modifier->modify($word);

            if ($modifier instanceof WhitelistFilter) {
                if (!empty($word) === true) {
                    // word is whitelisted
                    $this->addKeyword($word, $original);

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
     * @param string $keyword
     * @param string $original
     */
    public function addKeyword(string $keyword, string $original): void
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
     * @param string $keyword
     *
     * @return bool
     */
    private function keywordExists(string $keyword): bool
    {
        return array_key_exists($keyword, $this->getKeywords());
    }

    /**
     * @param array $keywords
     */
    public function setKeywords(array $keywords): void
    {
        $this->keywords = $keywords;
    }
}
