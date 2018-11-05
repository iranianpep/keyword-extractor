# Keyword Extractor
A package to extract keywords from text

[![Latest Stable Version](https://poser.pugx.org/keyword-extractor/keyword-extractor/v/stable)](https://packagist.org/packages/keyword-extractor/keyword-extractor)
[![Build Status](https://travis-ci.org/iranianpep/keyword-extractor.svg?branch=master)](https://travis-ci.org/iranianpep/keyword-extractor)
[![Build Status](https://scrutinizer-ci.com/g/iranianpep/keyword-extractor/badges/build.png?b=master)](https://scrutinizer-ci.com/g/iranianpep/keyword-extractor/build-status/master)
[![Code Climate](https://codeclimate.com/github/iranianpep/keyword-extractor/badges/gpa.svg)](https://codeclimate.com/github/iranianpep/keyword-extractor)
[![Test Coverage](https://codeclimate.com/github/iranianpep/keyword-extractor/badges/coverage.svg)](https://codeclimate.com/github/iranianpep/keyword-extractor/coverage)
[![Code Coverage](https://scrutinizer-ci.com/g/iranianpep/keyword-extractor/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/iranianpep/keyword-extractor/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/iranianpep/keyword-extractor/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/iranianpep/keyword-extractor/?branch=master)
[![Issue Count](https://codeclimate.com/github/iranianpep/keyword-extractor/badges/issue_count.svg)](https://codeclimate.com/github/iranianpep/keyword-extractor)
[![License](https://poser.pugx.org/keyword-extractor/keyword-extractor/license)](https://packagist.org/packages/keyword-extractor/keyword-extractor)
[![StyleCI](https://styleci.io/repos/152369408/shield?branch=master)](https://styleci.io/repos/152369408)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/f6798ce3c00e4de083d89f289b6c9285)](https://www.codacy.com/app/iranianpep/keyword-extractor?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=iranianpep/keyword-extractor&amp;utm_campaign=Badge_Grade)
[![Packagist](https://img.shields.io/packagist/dt/keyword-extractor/keyword-extractor.svg)](https://packagist.org/packages/keyword-extractor/keyword-extractor)
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/iranianpep/keyword-extractor/master/LICENSE)

## Server Requirements
- PHP >= 7.1

## Usage
-  To install ths package:
```
composer require keyword-extractor/keyword-extractor
```

- Extract the keywords:
```
$keywordExtractor = new KeywordExtractor();
$text = 'This is a simple sentence.';
$result = $keywordExtractor->run($text);
```

The result with the default modifiers will be:
```
array (
  'simpl' => 
  array (
    'frequency' => 1,
    'originals' => 
    array (
      0 => 'simple',
    ),
  ),
  'sentenc' => 
  array (
    'frequency' => 1,
    'originals' => 
    array (
      0 => 'sentence.',
    ),
  ),
)
```

Currently the default modifiers are as follow (they will be applied to the tokens in order):
```
[
    new EmailFilter(),
    new PunctuationFilter(),
    new WhitelistFilter($this->getWhitelist()),
    new BlacklistFilter($this->getBlacklist()),
    new StopWordFilter(),
    new NumberFilter(),
    new StemFilter(),
    // run the blacklist even after stemming too
    new BlacklistFilter($this->getBlacklist()),
]
```

Obviously, you can set your own modifiers:
```
$keywordExtractor->setModifiers([new PunctuationFilter()]);
```

Also whitelist can be used as follow:
```
$keywordExtractor = new KeywordExtractor();
$text = 'This is a simple sentence and simple sentence.';
$keywordExtractor->setWhitelist(['simple']);
$result = $keywordExtractor->run($text);
```

Which results in:
```
array (
  'simple' => 
  array (
    'frequency' => 2,
    'originals' => 
    array (
      0 => 'simple',
    ),
  ),
  'sentenc' => 
  array (
    'frequency' => 2,
    'originals' => 
    array (
      0 => 'sentence',
      1 => 'sentence.',
    ),
  ),
)
```

Blacklist can also be used in the same way as whitelist:
```
$keywordExtractor = new KeywordExtractor();
$text = 'This is a simple sentence.';
$keywordExtractor->setBlacklist(['simple']);
$result = $keywordExtractor->run($text);
```

And the result is:
```
array (
  'sentenc' => 
  array (
    'frequency' => 1,
    'originals' => 
    array (
      0 => 'sentence.',
    ),
  ),
)
```
