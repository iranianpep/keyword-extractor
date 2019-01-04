<?php

namespace KeywordExtractor;

use KeywordExtractor\Modifiers\Filters\EmailFilter;
use KeywordExtractor\Modifiers\Filters\PunctuationFilter;
use PHPUnit\Framework\TestCase;

class KeywordExtractorTest extends TestCase
{
    public function testRun()
    {
        $keywordExtractor = new KeywordExtractor();
        $text = 'This is a simple sentence.';
        $result = $keywordExtractor->run($text);

        $this->assertEquals([
            'simpl' => [
                'frequency' => 1,
                'originals' => [
                    'simple',
                ],
            ],
            'sentenc' => [
                'frequency' => 1,
                'originals' => [
                    'sentence.',
                ],
            ],
        ], $result);

        $text = '';
        $result = $keywordExtractor->run($text);

        $this->assertEquals([], $result);

        $text = '123 this text has got visual studio 2018 and 2019 and more numbers like 12 13 145';
        $result = $keywordExtractor->run($text);

        $this->assertEquals([
            'text' => [
                'frequency' => 1,
                'originals' => [
                    'text',
                ],
            ],
            'visual' => [
                'frequency' => 1,
                'originals' => [
                    'visual',
                ],
            ],
            'studio' => [
                'frequency' => 1,
                'originals' => [
                    'studio',
                ],
            ],
            'number' => [
                'frequency' => 1,
                'originals' => [
                    'numbers',
                ],
            ],
        ], $result);

        $keywordExtractor->setWhitelist(['visual studio 2018']);
        $result = $keywordExtractor->run($text);
        $this->assertEquals([
            'visual studio 2018' => [
                'frequency' => 1,
                'originals' => [
                    'visual studio 2018',
                ],
            ],
            'text' => [
                'frequency' => 1,
                'originals' => [
                    'text',
                ],
            ],
            'number' => [
                'frequency' => 1,
                'originals' => [
                    'numbers',
                ],
            ],
        ], $result);

        $keywordExtractor->setWhitelist(['2018 and 2019']);
        $result = $keywordExtractor->run($text);
        $this->assertEquals([
            '2018 and 2019' => [
                'frequency' => 1,
                'originals' => [
                    '2018 and 2019',
                ],
            ],
            'text' => [
                'frequency' => 1,
                'originals' => [
                    'text',
                ],
            ],
            'visual' => [
                'frequency' => 1,
                'originals' => [
                    'visual',
                ],
            ],
            'studio' => [
                'frequency' => 1,
                'originals' => [
                    'studio',
                ],
            ],
            'number' => [
                'frequency' => 1,
                'originals' => [
                    'numbers',
                ],
            ],
        ], $result);

        $text = 'This is a text with an email like: example@example.com in it.';
        $result = $keywordExtractor->run($text);

        $this->assertEquals([
            'text' => [
                'frequency' => 1,
                'originals' => [
                    'text',
                ],
            ],
            'email' => [
                'frequency' => 1,
                'originals' => [
                    'email',
                ],
            ],
        ], $result);

        $text = 'This is a text with two emails: example.example@example.com, and another@example.com.';
        $result = $keywordExtractor->run($text);

        $this->assertEquals([
            'text' => [
                'frequency' => 1,
                'originals' => [
                    'text',
                ],
            ],
            'email' => [
                'frequency' => 1,
                'originals' => [
                    'emails:',
                ],
            ],
        ], $result);
    }

    public function testRunWithWhitelist()
    {
        $keywordExtractor = new KeywordExtractor();
        $text = 'This is a simple sentence and simple sentence.';
        $keywordExtractor->setWhitelist([]);
        $result = $keywordExtractor->run($text);

        $this->assertEquals([
            'simpl' => [
                'frequency' => 2,
                'originals' => [
                    'simple',
                ],
            ],
            'sentenc' => [
                'frequency' => 2,
                'originals' => [
                    'sentence',
                    'sentence.',
                ],
            ],
        ], $result);

        $keywordExtractor->setWhitelist(['simple']);
        $result = $keywordExtractor->run($text);

        $this->assertEquals([
            'simple' => [
                'frequency' => 2,
                'originals' => [
                    'simple',
                ],
            ],
            'sentenc' => [
                'frequency' => 2,
                'originals' => [
                    'sentence',
                    'sentence.',
                ],
            ],
        ], $result);

        $keywordExtractor->setWhitelist(['simple', 'is', 'dummy']);
        $result = $keywordExtractor->run($text);

        $this->assertEquals([
            'is' => [
                'frequency' => 1,
                'originals' => [
                    'is',
                ],
            ],
            'simple' => [
                'frequency' => 2,
                'originals' => [
                    'simple',
                ],
            ],
            'sentenc' => [
                'frequency' => 2,
                'originals' => [
                    'sentence',
                    'sentence.',
                ],
            ],
        ], $result);

        $keywordExtractor->setWhitelist(['simple sentence']);
        $result = $keywordExtractor->run($text);

        $this->assertEquals([
            'simple sentence' => [
                'frequency' => 2,
                'originals' => [
                    'simple sentence',
                    'simple sentence.',
                ],
            ],
        ], $result);
    }

    public function testRunWithBlacklist()
    {
        $keywordExtractor = new KeywordExtractor();
        $text = 'This is a simple sentence.';
        $keywordExtractor->setBlacklist([]);
        $result = $keywordExtractor->run($text);

        $this->assertEquals([
            'simpl' => [
                'frequency' => 1,
                'originals' => [
                    'simple',
                ],
            ],
            'sentenc' => [
                'frequency' => 1,
                'originals' => [
                    'sentence.',
                ],
            ],
        ], $result);

        $keywordExtractor->setBlacklist(['simple']);
        $result = $keywordExtractor->run($text);

        $this->assertEquals([
            'sentenc' => [
                'frequency' => 1,
                'originals' => [
                    'sentence.',
                ],
            ],
        ], $result);

        $keywordExtractor->setBlacklist(['simple', 'is', 'dummy']);
        $result = $keywordExtractor->run($text);

        $this->assertEquals([
            'sentenc' => [
                'frequency' => 1,
                'originals' => [
                    'sentence.',
                ],
            ],
        ], $result);

        $keywordExtractor->setBlacklist(['simple sentence']);
        $result = $keywordExtractor->run($text);

        $this->assertEquals([], $result);

        $text = 'Exciting opportunity';
        $keywordExtractor->setBlacklist([]);
        $keywordExtractor->setWhitelist([]);
        $result = $keywordExtractor->run($text);

        $this->assertEquals([
            'excit' => [
                'frequency' => 1,
                'originals' => [
                    'exciting',
                ],
            ],
            'opportun' => [
                'frequency' => 1,
                'originals' => [
                    'opportunity',
                ],
            ],
        ], $result);

        $keywordExtractor->setBlacklist(['opportun']);

        $result = $keywordExtractor->run($text);

        $this->assertEquals([
            'excit' => [
                'frequency' => 1,
                'originals' => [
                    'exciting',
                ],
            ],
        ], $result);
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

        $this->assertArrayHasKey('linux', $result);
        $this->assertArrayHasKey('php', $result);
        $this->assertArrayHasKey('composer', $result);
        $this->assertArrayHasKey('css', $result);
        $this->assertArrayHasKey('mongodb', $result);

        $this->assertArrayHasKey('environ', $result);

        $keywordExtractor->setBlacklist(['software', 'etc', 'environments']);
        $result = $keywordExtractor->run($text);

        $this->assertArrayNotHasKey('environ', $result);

        $text = 'This includes some keywords such as javascript,
        java, c#, php, android, python, jquery, c++, ruby-on-rails, c, r, objective-c,
        django, wpf, asp.net-mvc, python-3.x, html5, python-2.7, .htaccess, jsp, oop, go, iis, .htaccess., ios7, f#';

        $keywordExtractor->setBlacklist(['includes', 'keywords']);
        $keywordExtractor->setWhitelist(['jquery', 'iis']);
        $result = $keywordExtractor->run($text);

        /*
         * Did not use loop because if one of the tests fail, it's easier to find out which one failed
         */
        $this->assertArrayHasKey('javascript', $result);
        $this->assertArrayHasKey('java', $result);
        $this->assertArrayHasKey('c#', $result);
        $this->assertArrayHasKey('php', $result);
        $this->assertArrayHasKey('android', $result);
        $this->assertArrayHasKey('python', $result);
        $this->assertArrayHasKey('jquery', $result);
        $this->assertArrayHasKey('c++', $result);
        $this->assertArrayHasKey('c', $result);
        $this->assertArrayHasKey('r', $result);
        $this->assertArrayHasKey('objective-c', $result);
        $this->assertArrayHasKey('wpf', $result);
        $this->assertArrayHasKey('asp.net-mvc', $result);
        $this->assertArrayHasKey('python-3.x', $result);
        $this->assertArrayHasKey('html5', $result);
        $this->assertArrayHasKey('python-2.7', $result);
        $this->assertArrayHasKey('.htaccess', $result);
        $this->assertArrayHasKey('django', $result);
        $this->assertArrayHasKey('jsp', $result);
        $this->assertArrayHasKey('oop', $result);
        $this->assertArrayHasKey('go', $result);
        $this->assertArrayHasKey('iis', $result);
        $this->assertArrayHasKey('ios7', $result);
        $this->assertArrayHasKey('f#', $result);

        $text = 'Milestone IT is an industry leader in the provision of the highest quality software engineers.
        Right now,  we are seeking 2 developers to work on-site delivering Backend Microservices in Node.

What do you need for these ones?

Demonstrated experience delivering in React or React Native - if you don\'t have React Native, this is a great opportunity to learn
Proven experience in end-to-end app development, with a natural flair for UX (PHP, HTML5, JavaScript, Node, CSS and/or equivalents)
Experience in best practice UI and responsive design with a passion for detail and aesthetics
Experience in rapid prototyping within frameworks such as React.js or React Native
Understanding of designing and developing microservices
Understanding of DevOps, Continuous Delivery and Lean Start-up principles
Experience managing applications on Amazon Web Services is held in high regard
Experience integrating chatbots and/or virtual assistants is a plus
Examples of work on GitHub is highly regarded
Redux, React, Angular, Node, etc etc.
If you want to be a part of this exciting and high-octane time, with a great business
who has great people than this is an opportunity you need to explore further...';

        // reset whitelist and blacklist
        $keywordExtractor->setBlacklist([]);
        $keywordExtractor->setWhitelist(['react native']);

        $result = $keywordExtractor->run($text);

        $this->assertArrayHasKey('microservic', $result);
        $this->assertArrayHasKey('react', $result);
        $this->assertArrayHasKey('react native', $result);
        $this->assertArrayHasKey('css', $result);
        $this->assertArrayHasKey('devop', $result);
        $this->assertArrayHasKey('redux', $result);

        $text = "What we're interested is c#, .net and asp but mainly c# and c#.";
        $keywordExtractor->setWhitelist(['c#', '.net', 'asp']);
        $keywordExtractor->setBlacklist(['interest', 'c#,']);
        $result = $keywordExtractor->run($text);

        $this->assertEquals([
            'c#' => [
                'frequency' => 3,
                'originals' => [
                    'c#,',
                    'c#',
                    'c#.',
                ],
            ],
            '.net' => [
                'frequency' => 1,
                'originals' => [
                    '.net',
                ],
            ],
            'asp' => [
                'frequency' => 1,
                'originals' => [
                    'asp',
                ],
            ],
        ], $result);
    }

    public function testGetModifiers()
    {
        $modifiers = [
            new EmailFilter(),
            new PunctuationFilter(),
        ];

        $keywordExtractor = new KeywordExtractor();
        $keywordExtractor->setModifiers($modifiers);

        $this->assertEquals($modifiers, $keywordExtractor->getModifiers());
    }
}
