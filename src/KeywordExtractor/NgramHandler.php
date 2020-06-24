<?php

namespace KeywordExtractor;

/**
 * Class NgramHandler.
 */
class NgramHandler
{
    /**
     * Generate n-grams
     * Credits to https://github.com/yooper/php-text-analysis/blob/master/src/NGrams/NGramFactory.php.
     *
     * @param array $tokens
     * @param int   $ngramSize
     *
     * @return array
     */
    public function generateNgrams(array $tokens, int $ngramSize): array
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
     * @return Ngram
     */
    private function extractNgram(array $tokens, $ngramSize, $currentIndex): Ngram
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

        return new Ngram($word, $subIndexes);
    }
}
