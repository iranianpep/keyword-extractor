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

//        $text = '';
//        $result = $keywordExtractor->run($text);
//
//        $this->assertEquals([], $result);
//
//        $text = '123 this text has got visual studio 2018 and 2019 and more numbers like 12 13 145';
//        $result = $keywordExtractor->run($text);
//
//        $this->assertEquals(['text', 'visual', 'studio', 'number'], $result);
//
//        $keywordExtractor->setWhitelist(['visual studio 2018']);
//        $result = $keywordExtractor->run($text);
//        $this->assertEquals(['visual studio 2018', 'text', 'number'], $result);
//
//        $keywordExtractor->setWhitelist(['2018 and 2019']);
//        $result = $keywordExtractor->run($text);
//        $this->assertEquals(['2018 and 2019', 'text', 'visual', 'studio', 'number'], $result);
//
//        $text = 'This is a text with an email like: example@example.com in it.';
//        $result = $keywordExtractor->run($text);
//
//        $this->assertEquals(['text', 'email'], $result);
//
//        $text = 'This is a text with two emails: example.example@example.com, and another@example.com.';
//        $result = $keywordExtractor->run($text);
//
//        $this->assertEquals(['text', 'email'], $result);
    }

//    public function testRunWithWhitelist()
//    {
//        $keywordExtractor = new KeywordExtractor();
//        $text = 'This is a simple sentence.';
//        $keywordExtractor->setWhitelist([]);
//        $result = $keywordExtractor->run($text);
//
//        $this->assertEquals(['simpl', 'sentenc'], $result);
//
//        $keywordExtractor->setWhitelist(['simple']);
//        $result = $keywordExtractor->run($text);
//
//        $this->assertEquals(['simple', 'sentenc'], $result);
//
//        $keywordExtractor->setWhitelist(['simple', 'is', 'dummy']);
//        $result = $keywordExtractor->run($text);
//
//        $this->assertEquals(['is', 'simple', 'sentenc'], $result);
//
//        $keywordExtractor->setWhitelist(['simple sentence']);
//        $result = $keywordExtractor->run($text);
//
//        $this->assertEquals(['simple sentence'], $result);
//    }
//
//    public function testRunWithBlacklist()
//    {
//        $keywordExtractor = new KeywordExtractor();
//        $text = 'This is a simple sentence.';
//        $keywordExtractor->setBlacklist([]);
//        $result = $keywordExtractor->run($text);
//
//        $this->assertEquals(['simpl', 'sentenc'], $result);
//
//        $keywordExtractor->setBlacklist(['simple']);
//        $result = $keywordExtractor->run($text);
//
//        $this->assertEquals(['sentenc'], $result);
//
//        $keywordExtractor->setBlacklist(['simple', 'is', 'dummy']);
//        $result = $keywordExtractor->run($text);
//
//        $this->assertEquals(['sentenc'], $result);
//
//        $keywordExtractor->setBlacklist(['simple sentence']);
//        $result = $keywordExtractor->run($text);
//
//        $this->assertEquals([], $result);
//
//        $text = 'Exciting opportunity';
//        $keywordExtractor->setBlacklist([]);
//        $keywordExtractor->setWhitelist([]);
//        $result = $keywordExtractor->run($text);
//
//        $this->assertEquals(['excit', 'opportun'], $result);
//
//        $keywordExtractor->setBlacklist(['opportun']);
//
//        $result = $keywordExtractor->run($text);
//
//        $this->assertEquals(['excit'], $result);
//    }
//
//    public function testRunWithWhitelistAndBlackList()
//    {
//        $keywordExtractor = new KeywordExtractor();
//
//        $text = 'Experience with all of the following technologies is a requirement:
//Linux
//PHP (incl. Composer, PHPUnit, Xdebug)
//nginx
//MySQL
//HTML, CSS, JavaScript
//Version Control Software (git, bzr, etc)
//
//Magento
//JavaScript libraries and frameworks such as jQuery, Ember or ReactJS
//PHP frameworks such as Zend, Laravel or CodeIgniter
//Phing, Ant, Grunt or other build management tools
//Docker, Kubernetes or other container environments
//Cloud services such as AWS, Google Cloud or Azure
//C, Python or other programming languages
//PostgreSQL, MongoDB
//PhpStorm, Eclipse or other IDE';
//
//        $keywordExtractor->setWhitelist(['version control', 'php', 'composer', 'google cloud']);
//        $keywordExtractor->setBlacklist(['software', 'etc']);
//
//        //for ($i = 0; $i < 500; $i++) {
//        $result = $keywordExtractor->run($text);
//        //}
//
//        $this->assertTrue(in_array('linux', $result));
//        $this->assertTrue(in_array('php', $result));
//        $this->assertTrue(in_array('composer', $result));
//        $this->assertTrue(in_array('css', $result));
//        $this->assertTrue(in_array('mongodb', $result));
//
//        $this->assertTrue(in_array('environ', $result));
//
//        $keywordExtractor->setBlacklist(['software', 'etc', 'environments']);
//        $result = $keywordExtractor->run($text);
//
//        $this->assertFalse(in_array('environ', $result));
//
//        $text = 'This includes some keywords such as javascript,
//        java, c#, php, android, python, jquery, c++, ruby-on-rails, c, r, objective-c,
//        django, wpf, asp.net-mvc, python-3.x, html5, python-2.7, .htaccess, jsp, oop, go, iis, .htaccess., ios7, f#';
//
//        $keywordExtractor->setBlacklist(['includes', 'keywords']);
//        $keywordExtractor->setWhitelist(['jquery', 'iis']);
//        $result = $keywordExtractor->run($text);
//
//        /*
//         * Did not use loop because if one of the tests fail, it's easier to find out which one failed
//         */
//        $this->assertTrue(in_array('javascript', $result) === true);
//        $this->assertTrue(in_array('java', $result) === true);
//        $this->assertTrue(in_array('c#', $result) === true);
//        $this->assertTrue(in_array('php', $result) === true);
//        $this->assertTrue(in_array('android', $result) === true);
//        $this->assertTrue(in_array('python', $result) === true);
//        $this->assertTrue(in_array('jquery', $result) === true);
//        $this->assertTrue(in_array('c++', $result) === true);
//        $this->assertTrue(in_array('c', $result) === true);
//        $this->assertTrue(in_array('r', $result) === true);
//        $this->assertTrue(in_array('objective-c', $result) === true);
//        $this->assertTrue(in_array('wpf', $result) === true);
//        $this->assertTrue(in_array('asp.net-mvc', $result) === true);
//        $this->assertTrue(in_array('python-3.x', $result) === true);
//        $this->assertTrue(in_array('html5', $result) === true);
//        $this->assertTrue(in_array('python-2.7', $result) === true);
//        $this->assertTrue(in_array('.htaccess', $result) === true);
//        $this->assertTrue(in_array('django', $result) === true);
//        $this->assertTrue(in_array('jsp', $result) === true);
//        $this->assertTrue(in_array('oop', $result) === true);
//        $this->assertTrue(in_array('go', $result) === true);
//        $this->assertTrue(in_array('iis', $result) === true);
//        $this->assertTrue(in_array('ios7', $result) === true);
//        $this->assertTrue(in_array('f#', $result) === true);
//
//        $text = 'Milestone IT is an industry leader in the provision of the highest quality software engineers.
//        Right now,  we are seeking 2 developers to work on-site delivering Backend Microservices in Node.
//
//What do you need for these ones?
//
//Demonstrated experience delivering in React or React Native - if you don\'t have React Native, this is a great opportunity to learn
//Proven experience in end-to-end app development, with a natural flair for UX (PHP, HTML5, JavaScript, Node, CSS and/or equivalents)
//Experience in best practice UI and responsive design with a passion for detail and aesthetics
//Experience in rapid prototyping within frameworks such as React.js or React Native
//Understanding of designing and developing microservices
//Understanding of DevOps, Continuous Delivery and Lean Start-up principles
//Experience managing applications on Amazon Web Services is held in high regard
//Experience integrating chatbots and/or virtual assistants is a plus
//Examples of work on GitHub is highly regarded
//Redux, React, Angular, Node, etc etc.
//If you want to be a part of this exciting and high-octane time, with a great business
//who has great people than this is an opportunity you need to explore further...';
//
//        // reset whitelist and blacklist
//        $keywordExtractor->setBlacklist([]);
//        $keywordExtractor->setWhitelist(['react native']);
//
//        $result = $keywordExtractor->run($text);
//
//        $this->assertTrue(in_array('microservic', $result) === true);
//        $this->assertTrue(in_array('react', $result) === true);
//        $this->assertTrue(in_array('react native', $result) === true);
//        $this->assertTrue(in_array('css', $result) === true);
//        $this->assertTrue(in_array('devop', $result) === true);
//        $this->assertTrue(in_array('redux', $result) === true);
//    }
}
