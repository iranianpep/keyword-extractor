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
}
