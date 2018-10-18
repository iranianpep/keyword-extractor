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

    /*
     * @param $string
     *
     * @return null|string
     */
    public function removeEmails($string):? string
    {
        $pattern = '/(?:[A-Za-z0-9!#$%&\'*+=?^_`{|}~-]+(?:\.[A-Za-z0-9!#$%&\'*+=?^_`{|}~-]+)*|\"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\")@(?:(?:[A-Za-z0-9](?:[A-Za-z0-9-]*[A-Za-z0-9])?\.)+[A-Za-z0-9](?:[A-Za-z0-9-]*[A-Za-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[A-Za-z0-9-]*[A-Za-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/';

        return preg_replace($pattern, '', $string);
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
