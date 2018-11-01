<?php

namespace KeywordExtractor;

class Filter
{
    /**
     * @param $word
     *
     * @return string
     */
    public function removeRightPunctuation($word): string
    {
        $searchFor = [
            '!', '$', '%', '&', '(', ')', '*', "'", ',',
            '\\', '-', '.', '/', ':', ';', '<', '=', '>', '?', '@',
            '^', '_', '`', '{', '|', '}', '~', '[', ']',
        ];

        return rtrim($word, " \t\n\r\0\x0B".implode('', $searchFor));
    }

    /**
     * @param array $words
     * @param array $whitelist
     *
     * @return array
     */
    public function removeRightPunctuations(array $words, array $whitelist = []): array
    {
        foreach ($words as $key => $word) {
            if (in_array($word, $whitelist)) {
                continue;
            }

            $words[$key] = $this->removeRightPunctuation($word);
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
        //return array_values($words);
        return $words;
    }

    /*
     * @param $string
     *
     * @return null|string
     */
    public function removeEmails($string):? string
    {
        // break the pattern to avoid exceeding the line
        $pattern = '/(?:[A-Za-z0-9!#$%&\'*+=?^_`{|}~-]+(?:\.[A-Za-z0-9!#$%&\'*+=?^_`{|}~-]+)*';
        $pattern .= '|\"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]';
        $pattern .= '|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\")@(?:(?:[A-Za-z0-9]';
        $pattern .= '(?:[A-Za-z0-9-]*[A-Za-z0-9])?\.)+[A-Za-z0-9](?:[A-Za-z0-9-]*[A-Za-z0-9])?';
        $pattern .= '|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}';
        $pattern .= '(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?';
        $pattern .= '|[A-Za-z0-9-]*[A-Za-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]';
        $pattern .= '|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/';

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
