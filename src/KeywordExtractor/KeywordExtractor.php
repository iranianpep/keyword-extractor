<?php

namespace KeywordExtractor;

use Exception;
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
    /**
     * Note: the order is important.
     */
    const NGRAM_SIZES = [3, 2, 1];

    private array $blacklist = [];
    private array $whitelist = [];
    private array $modifiers;
    private array $keywords;

    /**
     * @param string $string
     * @param string $sortBy
     * @param string $sortDir
     *
     * @throws Exception
     *
     * @return array
     */
    public function extract(string $string, string $sortBy = '', string $sortDir = Sorter::SORT_DIR_ASC): array
    {
        // reset the keywords
        $this->keywords = [];

        // lowercase and tokenize
        $string = (new LowerCaseTransformer())->modify($string);
        $tokens = (new TokenTransformer())->modify($string);

        // n grams can be passed as an arg to the constructor
        foreach (self::NGRAM_SIZES as $ngramSize) {
            $tokens = $this->extractNgramKeywords($tokens, $ngramSize);
        }

        if (empty($sortBy)) {
            return $this->keywords;
        }

        $this->keywords = (new Sorter())->sort($this->keywords, $sortBy, $sortDir);

        return $this->keywords;
    }

    public function extractKeywordsOnly(string $string, string $sortBy = '', string $sortDir = Sorter::SORT_DIR_ASC): array
    {
        $this->extract($string, $sortBy, $sortDir);

        return $this->retrieveKeywords();
    }

    /**
     * Retrieve flattened and clean keywords. The process includes the following steps:
     * - flatten the keywords
     * - trim punctuation marks
     * - remove duplicates
     *
     * @return array
     */
    private function retrieveKeywords(): array
    {
        if (empty($this->keywords)) {
            return [];
        }

        $keywords = array_reduce($this->keywords, function ($flattened, $structure) {
            $shortest = $this->findShortest($structure['occurrences']);
            $trimmed = preg_replace('/^[^\p{L}0-9]+|[^\p{L}0-9]+\z/u', '', $shortest);
            $flattened[] = $trimmed;

            return $flattened;
        });

        return $keywords;
    }

    /**
     * @param array{indexes: number, ngram: string} $occurrences
     *
     * @return string
     */
    private function findShortest(array $occurrences): string
    {
        $first = array_pop($occurrences);

        return array_reduce($occurrences, function ($shortest, $occurrence) {
            if (strlen($occurrence['ngram']) < strlen($shortest)) {
                $shortest = $occurrence['ngram'];
            }

            return $shortest;
        }, $first['ngram']);
    }

    /**
     * @param array $tokens
     * @param int   $ngramSize
     *
     * @return array
     */
    private function extractNgramKeywords(array $tokens, int $ngramSize): array
    {
        /** @var Ngram $ngram */
        foreach ((new NgramHandler())->generateNgrams($tokens, $ngramSize) as $ngram) {
            $result = $this->applyModifiers($tokens, $ngram);

            $tokens = $result['tokens'];
            $modifiedWord = $result['word'];
            $alreadyAdded = $result['alreadyAdded'];

            // if the word survives after applying all the filters it's deserved to be added to the keywords!
            if ($ngramSize === 1 && !empty($modifiedWord) && $alreadyAdded === false) {
                $this->addKeyword($modifiedWord, $ngram);
            }
        }

        return $tokens;
    }

    /**
     * @param array $tokens
     * @param Ngram $ngram
     *
     * @return array
     */
    private function applyModifiers(array $tokens, Ngram $ngram): array
    {
        $alreadyAdded = false;
        $word = $ngram->getWord();

        /** @var ModifierInterface */
        foreach ($this->getModifiers() as $modifier) {
            $toBeModified = $word;
            $word = $modifier->modify($word);

            if ($modifier instanceof WhitelistFilter) {
                if (!empty($word) === true) {
                    // word is whitelisted
                    $this->addKeyword($word, $ngram);

                    $tokens = (new IndexBlacklistFilter($ngram->getIndexes()))->modifyTokens($tokens);
                    $alreadyAdded = true;
                    break;
                }

                // word is NOT whitelisted - reset the empty word to the state before applying the whitelist
                $word = $toBeModified;
            }

            if ($modifier instanceof BlacklistFilter && empty($word)) {
                $tokens = (new IndexBlacklistFilter($ngram->getIndexes()))->modifyTokens($tokens);

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
     * @param string $keyword
     * @param Ngram  $originalNgram
     */
    public function addKeyword(string $keyword, Ngram $originalNgram): void
    {
        // initialise
        $frequency = 1;
        $occurrences = [];

        if ($this->keywordExists($keyword) === true) {
            $frequency = $this->keywords[$keyword]['frequency'] + 1;
            $occurrences = $this->keywords[$keyword]['occurrences'];
        }

        $occurrences[] = [
            'ngram'   => $originalNgram->getWord(),
            'indexes' => $originalNgram->getIndexes(),
        ];

        $this->keywords[$keyword] = [
            'frequency'   => $frequency,
            'occurrences' => $occurrences,
        ];
    }

    /**
     * @param string $keyword
     *
     * @return bool
     */
    private function keywordExists(string $keyword): bool
    {
        return array_key_exists($keyword, $this->keywords);
    }
}
