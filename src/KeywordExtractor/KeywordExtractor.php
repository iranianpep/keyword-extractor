<?php

namespace KeywordExtractor;

class KeywordExtractor
{
    public function hello(): string
    {
        return 'hi';
    }

    public function generateNgram($tokens, $ngram = 2)
    {
        $ngrams = [];
        for ($i = 0; $i <= count($tokens); $i = $i + ($ngram - 1)) {

            $text = '';
            for ($j = $i - ($ngram - 1); $j < $i + ($ngram - 1); $j++) {
                if (!isset($tokens[$j])) {
                    $text = '';
                    break;
                }

                $text .= $tokens[$j] . ' ';
            }

            if (!empty($text)) {
                $ngrams[] = trim($text);
            }
        }

        return $ngrams;
    }
}
