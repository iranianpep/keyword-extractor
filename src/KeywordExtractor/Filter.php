<?php

namespace KeywordExtractor;

class Filter
{
    /**
     * @param $word
     *
     * @return string
     */
    public function removePunctuation($word): string
    {
        $searchFor = [
            '!','#','$','%','&','(',')','*','+',"'",',',
            '\\','-','.','/',':',';','<','=','>','?','@',
            '^','_','`','{','|','}','~','[',']'
        ];

        return trim($word, " \t\n\r\0\x0B" . implode('', $searchFor));
    }

    /**
     * @param $words
     *
     * @return array
     */
    public function removePunctuations(array $words): array
    {
        foreach ($words as $key => $word) {
            $words[$key] = $this->removePunctuation($word);
        }

        return $words;
    }

    /**
     * @param $words
     *
     * @return array
     */
    public function removeNumbers(array $words): array
    {
        foreach ($words as $key => $word) {
            if (is_numeric($word) === true) {
                unset($words[$key]);
            }
        }

        // re-index the array
        return array_values($words);
    }

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

        // re-index the array
        return array_values($words);
    }
}
