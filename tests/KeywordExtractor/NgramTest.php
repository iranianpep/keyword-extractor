<?php

namespace KeywordExtractor;

use PHPUnit\Framework\TestCase;

class NgramTest extends TestCase
{
    private $ngram;

    public function setUp(): void
    {
        parent::setUp();

        $this->ngram = new Ngram('simple', [3]);
    }

    public function testGetWord()
    {
        $this->assertEquals('simple', $this->ngram->getWord());
    }

    public function testGetIndexes()
    {
        $this->assertEquals([3], $this->ngram->getIndexes());
    }
}
