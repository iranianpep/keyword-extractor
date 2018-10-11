<?php

namespace KeywordExtractor;

class KeywordExtractor
{
//    public function generateNgrams(array $tokens, $ngram = 2): array
//    {
//        $ngrams = [];
//        $length = count($tokens) - $ngram + 1;
//        for ($i = 0; $i < $length; $i++) {
//            $slice = array_slice($tokens, $i, $ngram);
//
//            $ngrams[$i] = implode(' ', $slice);
//        }
//
//        return $ngrams;
//    }

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
    public function generateNgrams(array $tokens, $ngramSize, $separator = ' '): array
    {
        $separatorLength = strlen($separator);
        $length = count($tokens) - $ngramSize + 1;

        if ($length < 1) {
            return [];
        }

        // initialize the array
        $ngrams = array_fill(0, $length, '');

        for ($i = 0; $i < $length; $i++) {
            for ($j = 0; $j < $ngramSize; $j++) {
                $ngrams[$i] .= $tokens[$i + $j];

                if ($j < $ngramSize - $separatorLength) {
                    $ngrams[$i] .= $separator;
                }
            }
        }

        return $ngrams;
    }

    /**
     * @param $text
     *
     * @return string
     */
    public function removePunctuations($text): string
    {
        $searchFor = [
            '!','#','$','%','&','\(','\)','*','+',"\'",',',
            '\\','-','\.','\\/',':',';','<','=','>','?','@',
            '^','_','`','{','|','}','~','\[','\]'
        ];

        $regex = "/([".implode("", $searchFor)."])/";

//        (?!(\w)(.*)(\w))[.]
//
//
//(\w{1})(\.{1})(\w{1})
//
//(\w\.\w)|(\w\.\w)
        var_dump($regex);exit;
        return preg_replace($regex, '', $text);
    }
}
