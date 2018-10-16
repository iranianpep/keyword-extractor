<?php

namespace KeywordExtractor;

use PHPUnit\Framework\TestCase;

class KeywordExtractorTest extends TestCase
{
    public function testRun()
    {
        $keywordExtractor = new KeywordExtractor();
        $text = 'This is a simple sentence.';
        $result = $keywordExtractor->run($text);

        $this->assertEquals(['simpl', 'sentenc'], $result);

        $text = '';
        $result = $keywordExtractor->run($text);

        $this->assertEquals([], $result);

        $text = '123 this text has got visual studio 2018 and 2019 and more numbers like 12 13 145';
        $result = $keywordExtractor->run($text);

        $this->assertEquals(['text', 'visual', 'studio', 'number'], $result);

        $keywordExtractor->setWhitelist(['visual studio 2018']);
        $result = $keywordExtractor->run($text);
        $this->assertEquals(['visual studio 2018', 'text', 'number'], $result);

        $keywordExtractor->setWhitelist(['2018 and 2019']);
        $result = $keywordExtractor->run($text);
        $this->assertEquals(['2018 and 2019', 'text', 'visual', 'studio', 'number'], $result);
    }

    public function testRunWithWhitelist()
    {
        $keywordExtractor = new KeywordExtractor();
        $text = 'This is a simple sentence.';
        $keywordExtractor->setWhitelist([]);
        $result = $keywordExtractor->run($text);

        $this->assertEquals(['simpl', 'sentenc'], $result);

        $keywordExtractor->setWhitelist(['simple']);
        $result = $keywordExtractor->run($text);

        $this->assertEquals(['simple', 'sentenc'], $result);

        $keywordExtractor->setWhitelist(['simple', 'is', 'dummy']);
        $result = $keywordExtractor->run($text);

        $this->assertEquals(['is', 'simple', 'sentenc'], $result);

        $keywordExtractor->setWhitelist(['simple sentence']);
        $result = $keywordExtractor->run($text);

        $this->assertEquals(['simple sentence'], $result);
    }

    public function testRunWithBlacklist()
    {
        $keywordExtractor = new KeywordExtractor();
        $text = 'This is a simple sentence.';
        $keywordExtractor->setBlacklist([]);
        $result = $keywordExtractor->run($text);

        $this->assertEquals(['simpl', 'sentenc'], $result);

        $keywordExtractor->setBlacklist(['simple']);
        $result = $keywordExtractor->run($text);

        $this->assertEquals(['sentenc'], $result);

        $keywordExtractor->setBlacklist(['simple', 'is', 'dummy']);
        $result = $keywordExtractor->run($text);

        $this->assertEquals(['sentenc'], $result);

        $keywordExtractor->setBlacklist(['simple sentence']);
        $result = $keywordExtractor->run($text);

        $this->assertEquals([], $result);
    }

    public function testRunWithWhitelistAndBlackList()
    {
        $keywordExtractor = new KeywordExtractor();

        $text = 'Experience with all of the following technologies is a requirement:
Linux
PHP (incl. Composer, PHPUnit, Xdebug)
nginx
MySQL
HTML, CSS, JavaScript
Version Control Software (git, bzr, etc)

Magento
JavaScript libraries and frameworks such as jQuery, Ember or ReactJS
PHP frameworks such as Zend, Laravel or CodeIgniter
Phing, Ant, Grunt or other build management tools
Docker, Kubernetes or other container environments
Cloud services such as AWS, Google Cloud or Azure
C, Python or other programming languages
PostgreSQL, MongoDB
PhpStorm, Eclipse or other IDE';

        $keywordExtractor->setWhitelist(['version control', 'php', 'composer', 'google cloud']);
        $keywordExtractor->setBlacklist(['software', 'etc']);

        //for ($i = 0; $i < 500; $i++) {
            $result = $keywordExtractor->run($text);
        //}

        $this->assertTrue(in_array('linux', $result));
        $this->assertTrue(in_array('php', $result));
        $this->assertTrue(in_array('composer', $result));
        $this->assertTrue(in_array('css', $result));
        $this->assertTrue(in_array('mongodb', $result));

        $this->assertTrue(in_array('environ', $result));

        $keywordExtractor->setBlacklist(['software', 'etc', 'environments']);
        $result = $keywordExtractor->run($text);

        $this->assertFalse(in_array('environ', $result));
    }
}
