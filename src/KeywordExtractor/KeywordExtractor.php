<?php

namespace KeywordExtractor;

class KeywordExtractor
{
    public function generateNgram(array $tokens, $ngram = 2): array
    {
        $ngrams = [];
        $length = count($tokens) - $ngram + 1;
        for ($i = 0; $i < $length; $i++) {
            $slice = array_slice($tokens, $i, $ngram);

            $ngrams[$i] = implode(' ', $slice);
        }

        return $ngrams;
    }

    public function generateNgram2(array $tokens, $nGramSize, $separator = ' ') : array
    {
        $separatorLength = strlen($separator);
        $length = count($tokens) - $nGramSize + 1;
        if($length < 1) {
            return [];
        }
        $ngrams = array_fill(0, $length, ''); // initialize the array

        for($index = 0; $index < $length; $index++)
        {
            for($jindex = 0; $jindex < $nGramSize; $jindex++)
            {
                $ngrams[$index] .= $tokens[$index + $jindex];
                if($jindex < $nGramSize - $separatorLength) {
                    $ngrams[$index] .= $separator;
                }
            }
        }
        return $ngrams;
    }
}
