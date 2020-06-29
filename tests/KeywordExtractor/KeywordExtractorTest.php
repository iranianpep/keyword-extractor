<?php

namespace KeywordExtractor;

use Exception;
use KeywordExtractor\Modifiers\Filters\EmailFilter;
use KeywordExtractor\Modifiers\Filters\PunctuationFilter;
use PHPUnit\Framework\TestCase;

class KeywordExtractorTest extends TestCase
{
    private $keywordExtractor;

    public function setUp(): void
    {
        parent::setUp();

        $this->keywordExtractor = new KeywordExtractor();
    }

    public function testRun()
    {
        $text = 'This is a simple sentence.';
        $result = $this->keywordExtractor->run($text);

        $this->assertEquals([
            'simpl' => [
                'frequency'   => 1,
                'occurrences' => [
                    [
                        'indexes' => [
                            3,
                        ],
                        'ngram' => 'simple',
                    ],
                ],
            ],
            'sentenc' => [
                'frequency'   => 1,
                'occurrences' => [
                    [
                        'indexes' => [
                            4,
                        ],
                        'ngram' => 'sentence.',
                    ],
                ],
            ],
        ], $result);

        $text = '';
        $result = $this->keywordExtractor->run($text);

        $this->assertEquals([], $result);

        $text = '123 this text has got visual studio 2018 and 2019 and more numbers like 12 13 145';
        $result = $this->keywordExtractor->run($text);

        $this->assertEquals([
            'text' => [
                'frequency'   => 1,
                'occurrences' => [
                    [
                        'indexes' => [
                            2,
                        ],
                        'ngram' => 'text',
                    ],
                ],
            ],
            'visual' => [
                'frequency'   => 1,
                'occurrences' => [
                    [
                        'indexes' => [
                            5,
                        ],
                        'ngram' => 'visual',
                    ],
                ],
            ],
            'studio' => [
                'frequency'   => 1,
                'occurrences' => [
                    [
                        'indexes' => [
                            6,
                        ],
                        'ngram' => 'studio',
                    ],
                ],
            ],
            'number' => [
                'frequency'   => 1,
                'occurrences' => [
                    [
                        'indexes' => [
                            12,
                        ],
                        'ngram' => 'numbers',
                    ],
                ],
            ],
        ], $result);

        $this->keywordExtractor->setWhitelist(['visual studio 2018']);
        $result = $this->keywordExtractor->run($text);
        $this->assertEquals([
            'visual studio 2018' => [
                'frequency'   => 1,
                'occurrences' => [
                    [
                        'indexes' => [
                            5,
                            6,
                            7,
                        ],
                        'ngram' => 'visual studio 2018',
                    ],
                ],
            ],
            'text' => [
                'frequency'   => 1,
                'occurrences' => [
                    [
                        'indexes' => [
                            2,
                        ],
                        'ngram' => 'text',
                    ],
                ],
            ],
            'number' => [
                'frequency'   => 1,
                'occurrences' => [
                    [
                        'indexes' => [
                            12,
                        ],
                        'ngram' => 'numbers',
                    ],
                ],
            ],
        ], $result);

        $this->keywordExtractor->setWhitelist(['2018 and 2019']);
        $result = $this->keywordExtractor->run($text);
        $this->assertEquals([
            '2018 and 2019' => [
                'frequency'   => 1,
                'occurrences' => [
                    [
                        'indexes' => [
                            7,
                            8,
                            9,
                        ],
                        'ngram' => '2018 and 2019',
                    ],
                ],
            ],
            'text' => [
                'frequency'   => 1,
                'occurrences' => [
                    [
                        'indexes' => [
                            2,
                        ],
                        'ngram' => 'text',
                    ],
                ],
            ],
            'visual' => [
                'frequency'   => 1,
                'occurrences' => [
                    [
                        'indexes' => [
                            5,
                        ],
                        'ngram' => 'visual',
                    ],
                ],
            ],
            'studio' => [
                'frequency'   => 1,
                'occurrences' => [
                    [
                        'indexes' => [
                            6,
                        ],
                        'ngram' => 'studio',
                    ],
                ],
            ],
            'number' => [
                'frequency'   => 1,
                'occurrences' => [
                    [
                        'indexes' => [
                            12,
                        ],
                        'ngram' => 'numbers',
                    ],
                ],
            ],
        ], $result);

        $text = 'This is a text with an email like: example@example.com in it.';
        $result = $this->keywordExtractor->run($text);

        $this->assertEquals([
            'text' => [
                'frequency'   => 1,
                'occurrences' => [
                    [
                        'indexes' => [
                            3,
                        ],
                        'ngram' => 'text',
                    ],
                ],
            ],
            'email' => [
                'frequency'   => 1,
                'occurrences' => [
                    [
                        'indexes' => [
                            6,
                        ],
                        'ngram' => 'email',
                    ],
                ],
            ],
        ], $result);

        $text = 'This is a text with two emails: example.example@example.com, and another@example.com.';
        $result = $this->keywordExtractor->run($text);

        $this->assertEquals([
            'text' => [
                'frequency'   => 1,
                'occurrences' => [
                    [
                        'indexes' => [
                            3,
                        ],
                        'ngram' => 'text',
                    ],
                ],
            ],
            'email' => [
                'frequency'   => 1,
                'occurrences' => [
                    [
                        'indexes' => [
                            6,
                        ],
                        'ngram' => 'emails:',
                    ],
                ],
            ],
        ], $result);
    }

    public function testRunWithWhitelist()
    {
        $text = 'This is a simple sentence and simple sentence.';
        $this->keywordExtractor->setWhitelist([]);
        $result = $this->keywordExtractor->run($text);

        $this->assertEquals([
            'simpl' => [
                'frequency'   => 2,
                'occurrences' => [
                    [
                        'indexes' => [
                            3,
                        ],
                        'ngram' => 'simple',
                    ],
                    [
                        'indexes' => [
                            6,
                        ],
                        'ngram' => 'simple',
                    ],
                ],
            ],
            'sentenc' => [
                'frequency'   => 2,
                'occurrences' => [
                    [
                        'indexes' => [
                            4,
                        ],
                        'ngram' => 'sentence',
                    ],
                    [
                        'indexes' => [
                            7,
                        ],
                        'ngram' => 'sentence.',
                    ],
                ],
            ],
        ], $result);

        $this->keywordExtractor->setWhitelist(['simple']);
        $result = $this->keywordExtractor->run($text);

        $this->assertEquals([
            'simple' => [
                'frequency'   => 2,
                'occurrences' => [
                    [
                        'indexes' => [
                            3,
                        ],
                        'ngram' => 'simple',
                    ],
                    [
                        'indexes' => [
                            6,
                        ],
                        'ngram' => 'simple',
                    ],
                ],
            ],
            'sentenc' => [
                'frequency'   => 2,
                'occurrences' => [
                    [
                        'indexes' => [
                            4,
                        ],
                        'ngram' => 'sentence',
                    ],
                    [
                        'indexes' => [
                            7,
                        ],
                        'ngram' => 'sentence.',
                    ],
                ],
            ],
        ], $result);

        $this->keywordExtractor->setWhitelist(['simple', 'is', 'dummy']);
        $result = $this->keywordExtractor->run($text);

        $this->assertEquals([
            'is' => [
                'frequency'   => 1,
                'occurrences' => [
                    [
                        'indexes' => [
                            1,
                        ],
                        'ngram' => 'is',
                    ],
                ],
            ],
            'simple' => [
                'frequency'   => 2,
                'occurrences' => [
                    [
                        'indexes' => [
                            3,
                        ],
                        'ngram' => 'simple',
                    ],
                    [
                        'indexes' => [
                            6,
                        ],
                        'ngram' => 'simple',
                    ],
                ],
            ],
            'sentenc' => [
                'frequency'   => 2,
                'occurrences' => [
                    [
                        'indexes' => [
                            4,
                        ],
                        'ngram' => 'sentence',
                    ],
                    [
                        'indexes' => [
                            7,
                        ],
                        'ngram' => 'sentence.',
                    ],
                ],
            ],
        ], $result);

        $this->keywordExtractor->setWhitelist(['simple sentence']);
        $result = $this->keywordExtractor->run($text);

        $this->assertEquals([
            'simple sentence' => [
                'frequency'   => 2,
                'occurrences' => [
                    [
                        'indexes' => [
                            3,
                            4,
                        ],
                        'ngram' => 'simple sentence',
                    ],
                    [
                        'indexes' => [
                            6,
                            7,
                        ],
                        'ngram' => 'simple sentence.',
                    ],
                ],
            ],
        ], $result);
    }

    public function testRunWithBlacklist()
    {
        $text = 'This is a simple sentence.';
        $this->keywordExtractor->setBlacklist([]);
        $result = $this->keywordExtractor->run($text);

        $this->assertEquals([
            'simpl' => [
                'frequency'   => 1,
                'occurrences' => [
                    [
                        'indexes' => [
                            3,
                        ],
                        'ngram' => 'simple',
                    ],
                ],
            ],
            'sentenc' => [
                'frequency'   => 1,
                'occurrences' => [
                    [
                        'indexes' => [
                            4,
                        ],
                        'ngram' => 'sentence.',
                    ],
                ],
            ],
        ], $result);

        $this->keywordExtractor->setBlacklist(['simple']);
        $result = $this->keywordExtractor->run($text);

        $this->assertEquals([
            'sentenc' => [
                'frequency'   => 1,
                'occurrences' => [
                    [
                        'indexes' => [
                            4,
                        ],
                        'ngram' => 'sentence.',
                    ],
                ],
            ],
        ], $result);

        $this->keywordExtractor->setBlacklist(['simple', 'is', 'dummy']);
        $result = $this->keywordExtractor->run($text);

        $this->assertEquals([
            'sentenc' => [
                'frequency'   => 1,
                'occurrences' => [
                    [
                        'indexes' => [
                            4,
                        ],
                        'ngram' => 'sentence.',
                    ],
                ],
            ],
        ], $result);

        $this->keywordExtractor->setBlacklist(['simple sentence']);
        $result = $this->keywordExtractor->run($text);

        $this->assertEquals([], $result);

        $text = 'Exciting opportunity';
        $this->keywordExtractor->setBlacklist([]);
        $this->keywordExtractor->setWhitelist([]);
        $result = $this->keywordExtractor->run($text);

        $this->assertEquals([
            'excit' => [
                'frequency'   => 1,
                'occurrences' => [
                    [
                        'indexes' => [
                            0,
                        ],
                        'ngram' => 'exciting',
                    ],
                ],
            ],
            'opportun' => [
                'frequency'   => 1,
                'occurrences' => [
                    [
                        'indexes' => [
                            1,
                        ],
                        'ngram' => 'opportunity',
                    ],
                ],
            ],
        ], $result);

        $this->keywordExtractor->setBlacklist(['opportun']);

        $result = $this->keywordExtractor->run($text);

        $this->assertEquals([
            'excit' => [
                'frequency'   => 1,
                'occurrences' => [
                    [
                        'indexes' => [
                            0,
                        ],
                        'ngram' => 'exciting',
                    ],
                ],
            ],
        ], $result);
    }

    public function testRunWithWhitelistAndBlackList()
    {
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

        $this->keywordExtractor->setWhitelist(['version control', 'php', 'composer', 'google cloud']);
        $this->keywordExtractor->setBlacklist(['software', 'etc']);

        $result = $this->keywordExtractor->run($text);

        $this->assertArrayHasKey('linux', $result);
        $this->assertArrayHasKey('php', $result);
        $this->assertArrayHasKey('composer', $result);
        $this->assertArrayHasKey('css', $result);
        $this->assertArrayHasKey('mongodb', $result);

        $this->assertArrayHasKey('environ', $result);

        $this->keywordExtractor->setBlacklist(['software', 'etc', 'environments']);
        $result = $this->keywordExtractor->run($text);

        $this->assertArrayNotHasKey('environ', $result);

        $text = 'This includes some keywords such as javascript,
        java, c#, php, android, python, jquery, c++, ruby-on-rails, c, r, objective-c,
        django, wpf, asp.net-mvc, python-3.x, html5, python-2.7, .htaccess, jsp, oop, go, iis, .htaccess., ios7, f#';

        $this->keywordExtractor->setBlacklist(['includes', 'keywords']);
        $this->keywordExtractor->setWhitelist(['jquery', 'iis']);
        $result = $this->keywordExtractor->run($text);

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
        $this->keywordExtractor->setBlacklist([]);
        $this->keywordExtractor->setWhitelist(['react native']);

        $result = $this->keywordExtractor->run($text);

        $this->assertArrayHasKey('microservic', $result);
        $this->assertArrayHasKey('react', $result);
        $this->assertArrayHasKey('react native', $result);
        $this->assertArrayHasKey('css', $result);
        $this->assertArrayHasKey('devop', $result);
        $this->assertArrayHasKey('redux', $result);

        $text = "What we're interested is c#, .net and asp but mainly c# and c#.";
        $this->keywordExtractor->setWhitelist(['c#', '.net', 'asp']);
        $this->keywordExtractor->setBlacklist(['interest', 'c#,']);
        $result = $this->keywordExtractor->run($text);

        $this->assertEquals([
            'c#' => [
                'frequency'   => 3,
                'occurrences' => [
                    [
                        'indexes' => [
                            4,
                        ],
                        'ngram' => 'c#,',
                    ],
                    [
                        'indexes' => [
                            10,
                        ],
                        'ngram' => 'c#',
                    ],
                    [
                        'indexes' => [
                            12,
                        ],
                        'ngram' => 'c#.',
                    ],
                ],
            ],
            '.net' => [
                'frequency'   => 1,
                'occurrences' => [
                    [
                        'indexes' => [
                            5,
                        ],
                        'ngram' => '.net',
                    ],
                ],
            ],
            'asp' => [
                'frequency'   => 1,
                'occurrences' => [
                    [
                        'indexes' => [
                            7,
                        ],
                        'ngram' => 'asp',
                    ],
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

        $this->keywordExtractor->setModifiers($modifiers);

        $this->assertEquals($modifiers, $this->keywordExtractor->getModifiers());
    }

    public function testRunSortedByFrequency()
    {
        $text = '2 simple sentences and only one sentence.';
        $result = $this->keywordExtractor->run($text, Sorter::SORT_BY_FREQUENCY);

        $this->assertEquals(2, $result['sentenc']['frequency']);

        // result should be the same
        $text = '2 sentences and only one simple sentence.';
        $result = $this->keywordExtractor->run($text, Sorter::SORT_BY_FREQUENCY);

        $this->assertEquals(2, $result['sentenc']['frequency']);

        // default is asc
        $arrayItem = array_shift($result);
        $this->assertEquals($arrayItem['frequency'], 1);

        $arrayItem = array_shift($result);
        $this->assertEquals($arrayItem['frequency'], 2);

        $result = $this->keywordExtractor->run(
            $text,
            Sorter::SORT_BY_FREQUENCY,
            Sorter::SORT_DIR_DESC
        );

        // default is asc
        $arrayItem = array_shift($result);
        $this->assertEquals($arrayItem['frequency'], 2);

        $arrayItem = array_shift($result);
        $this->assertEquals($arrayItem['frequency'], 1);
    }

    /**
     * @throws Exception
     */
    public function testRunSortedByMidOccurrenceDistance()
    {
        $text = 'sentence and sentence';
        $result = $this->keywordExtractor->run($text, Sorter::SORT_BY_MIN_OCCURRENCE_DISTANCE);

        $this->assertEquals(1, $result['sentenc']['minOccurrencesDistance']);

        $text = 'sentence sentence';
        $result = $this->keywordExtractor->run($text, Sorter::SORT_BY_MIN_OCCURRENCE_DISTANCE);

        $this->assertEquals(0, $result['sentenc']['minOccurrencesDistance']);

        $text = 'sentence';
        $result = $this->keywordExtractor->run($text, Sorter::SORT_BY_MIN_OCCURRENCE_DISTANCE);

        $this->assertEquals(null, $result['sentenc']['minOccurrencesDistance']);

        $text = 'john james john john to james joe john joe';
        $result = $this->keywordExtractor->run(
            $text,
            Sorter::SORT_BY_MIN_OCCURRENCE_DISTANCE
        );

        $arrayItem = array_shift($result);
        // john
        $this->assertEquals(0, $arrayItem['minOccurrencesDistance']);

        $result = $this->keywordExtractor->run(
            $text,
            Sorter::SORT_BY_MIN_OCCURRENCE_DISTANCE,
            Sorter::SORT_DIR_DESC
        );

        $arrayItem = array_shift($result);
        // james
        $this->assertEquals(3, $arrayItem['minOccurrencesDistance']);
    }
}
