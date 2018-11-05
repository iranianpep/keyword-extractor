<?php

namespace KeywordExtractor\Modifiers\Filters;

use KeywordExtractor\Modifiers\AbstractModifier;

class StopWordFilter extends AbstractModifier
{
    const DS = DIRECTORY_SEPARATOR;
    const STORAGE_DIR = 'storage';

    private $stopWordList;

    public function modifyToken($token)
    {
        if (in_array($token, $this->getStopWordList()) === true) {
            return '';
        }

        return $token;
    }

    /**
     * @return array
     */
    public function getStopWordList(): array
    {
        if (!isset($this->stopWordList)) {
            $stopWordsPath = dirname(dirname(__DIR__)).self::DS.self::STORAGE_DIR.self::DS.'stopwords-en.json';
            $content = json_decode(file_get_contents($stopWordsPath), true);
            $this->setStopWordList($content);
        }

        return $this->stopWordList;
    }

    /**
     * @param mixed $stopWordList
     */
    public function setStopWordList(array $stopWordList): void
    {
        $this->stopWordList = $stopWordList;
    }
}
