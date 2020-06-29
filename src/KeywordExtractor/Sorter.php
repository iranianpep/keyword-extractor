<?php

namespace KeywordExtractor;

use Exception;

class Sorter
{
    const SORT_BY_VALUES = [
        self::SORT_BY_FREQUENCY,
        self::SORT_BY_MIN_OCCURRENCE_DISTANCE,
    ];
    const SORT_BY_FREQUENCY = 'frequency';
    const SORT_BY_MIN_OCCURRENCE_DISTANCE = 'minOccurrencesDistance';

    const SORT_DIR_VALUES = [
        self::SORT_DIR_ASC,
        self::SORT_DIR_DESC,
    ];
    const SORT_DIR_ASC = 'asc';
    const SORT_DIR_DESC = 'desc';

    /**
     * @param array  $array
     * @param string $sortBy
     * @param string $sortDir
     *
     * @throws Exception
     *
     * @return array
     */
    public function sort(array $array, string $sortBy, string $sortDir): array
    {
        // sortBy has been specified, validate both sortBy and sortDir
        $this->validateSortValues($sortBy, $sortDir);

        if ($sortBy === self::SORT_BY_MIN_OCCURRENCE_DISTANCE) {
            // calculate min occurrence distance
            $array = $this->addMinOccurrencesDistance($array);
        }

        // all good, sort
        uasort($array, function ($keywordA, $keywordB) use ($sortBy, $sortDir) {
            if ($sortDir === self::SORT_DIR_DESC) {
                return $keywordB[$sortBy] <=> $keywordA[$sortBy];
            }

            return $keywordA[$sortBy] <=> $keywordB[$sortBy];
        });

        return $array;
    }

    /**
     * @param string $sortBy
     *
     * @return bool
     */
    private function isSortByValid(string $sortBy): bool
    {
        return in_array($sortBy, self::SORT_BY_VALUES);
    }

    /**
     * @param string $sortDir
     *
     * @return bool
     */
    private function isSortDirValid(string $sortDir): bool
    {
        return in_array($sortDir, self::SORT_DIR_VALUES);
    }

    /**
     * @param string $sortBy
     * @param string $sortDir
     *
     * @throws Exception
     */
    private function validateSortValues(string $sortBy, string $sortDir): void
    {
        // sortBy has been specified
        if (!$this->isSortByValid($sortBy)) {
            throw new Exception(
                "Sort by value: {$sortBy} is not valid. Valid values: ".implode(',', self::SORT_BY_VALUES)
            );
        }

        // since sortBy has been specified, sortDir must be valid too
        if (!$this->isSortDirValid($sortDir)) {
            throw new Exception(
                "Sort direction value: {$sortDir} is not valid. Valid values: ".implode(',', self::SORT_DIR_VALUES)
            );
        }
    }

    /**
     * Calculate and add minOccurrencesDistance to keywords.
     *
     * @param array $array
     *
     * @return array
     */
    private function addMinOccurrencesDistance(array $array): array
    {
        foreach ($array as $keyword => $keywordInfo) {
            // if $minOccurrencesDistance is 1, it's the min distance already, don't bother with the calculation
            $compactIndexes = [];
            foreach ($keywordInfo['occurrences'] as $occurrence) {
                $compactIndexes[] = $occurrence['indexes'][0];
            }

            $difference = (new Utility())->findMinDiff($compactIndexes);
            // -1 is to only get the number of words between occurrences
            $array[$keyword][self::SORT_BY_MIN_OCCURRENCE_DISTANCE] = is_null($difference) ? null : $difference - 1;
        }

        return $array;
    }
}
