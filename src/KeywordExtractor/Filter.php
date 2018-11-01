<?php

namespace KeywordExtractor;

class Filter
{
    /**
     * @param $words
     * @param $indexes
     *
     * @return array
     */
    public function removeWordsByIndexes(array $words, array $indexes): array
    {
        foreach ($indexes as $index) {
            unset($words[$index]);
        }

        return $words;
    }

    /**
     * @param array $words
     *
     * @return array
     */
    public function removeEmptyArrayElements(array $words): array
    {
        foreach ($words as $key => $word) {
            if ($word === '' || ctype_space($word)) {
                unset($words[$key]);
            }
        }

        return array_values($words);
    }
}
