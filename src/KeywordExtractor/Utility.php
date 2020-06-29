<?php

namespace KeywordExtractor;

class Utility
{
    /**
     * @param array $array
     *
     * @return int
     */
    public function findMinDiff(array $array): ?int
    {
        $arrayLength = count($array);
        // if the elements are less than 2, does not make sense to have min diff
        if ($arrayLength < 2) {
            return null;
        }

        sort($array);

        $minDiff = $array[1] - $array[0];

        for ($i = 2; $i !== $arrayLength; $i++) {
            $minDiff = min($minDiff, $array[$i] - $array[$i - 1]);
        }

        return $minDiff;
    }
}
