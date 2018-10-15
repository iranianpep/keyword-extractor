<?php

namespace KeywordExtractor;

use NlpTools\Stemmers\PorterStemmer;
use NlpTools\Tokenizers\WhitespaceTokenizer;

class KeywordExtractor
{
    private $blacklist;
    private $whitelist;

    /**
     * order is important
     */
    const NGRAM_SIZES = [3, 2];
    const STOP_WORDS = [
        "a",
        "a's",
        "able",
        "about",
        "above",
        "according",
        "accordingly",
        "across",
        "actually",
        "after",
        "afterwards",
        "again",
        "against",
        "ain't",
        "all",
        "allow",
        "allows",
        "almost",
        "alone",
        "along",
        "already",
        "also",
        "although",
        "always",
        "am",
        "among",
        "amongst",
        "an",
        "and",
        "another",
        "any",
        "anybody",
        "anyhow",
        "anyone",
        "anything",
        "anyway",
        "anyways",
        "anywhere",
        "apart",
        "appear",
        "appreciate",
        "appropriate",
        "are",
        "aren't",
        "around",
        "as",
        "aside",
        "ask",
        "asking",
        "associated",
        "at",
        "available",
        "away",
        "awfully",
        "b",
        "be",
        "became",
        "because",
        "become",
        "becomes",
        "becoming",
        "been",
        "before",
        "beforehand",
        "behind",
        "being",
        "believe",
        "below",
        "beside",
        "besides",
        "best",
        "better",
        "between",
        "beyond",
        "both",
        "brief",
        "but",
        "by",
        "c",
        "c'mon",
        "c's",
        "came",
        "can",
        "can't",
        "cannot",
        "cant",
        "cause",
        "causes",
        "certain",
        "certainly",
        "changes",
        "clearly",
        "co",
        "com",
        "come",
        "comes",
        "concerning",
        "consequently",
        "consider",
        "considering",
        "contain",
        "containing",
        "contains",
        "corresponding",
        "could",
        "couldn't",
        "course",
        "currently",
        "d",
        "definitely",
        "described",
        "despite",
        "did",
        "didn't",
        "different",
        "do",
        "does",
        "doesn't",
        "doing",
        "don't",
        "done",
        "down",
        "downwards",
        "during",
        "e",
        "each",
        "edu",
        "eg",
        "eight",
        "either",
        "else",
        "elsewhere",
        "enough",
        "entirely",
        "especially",
        "et",
        "etc",
        "even",
        "ever",
        "every",
        "everybody",
        "everyone",
        "everything",
        "everywhere",
        "ex",
        "exactly",
        "example",
        "except",
        "f",
        "far",
        "few",
        "fifth",
        "first",
        "five",
        "followed",
        "following",
        "follows",
        "for",
        "former",
        "formerly",
        "forth",
        "four",
        "from",
        "further",
        "furthermore",
        "g",
        "get",
        "gets",
        "getting",
        "given",
        "gives",
        "go",
        "goes",
        "going",
        "gone",
        "got",
        "gotten",
        "greetings",
        "h",
        "had",
        "hadn't",
        "happens",
        "hardly",
        "has",
        "hasn't",
        "have",
        "haven't",
        "having",
        "he",
        "he's",
        "hello",
        "help",
        "hence",
        "her",
        "here",
        "here's",
        "hereafter",
        "hereby",
        "herein",
        "hereupon",
        "hers",
        "herself",
        "hi",
        "him",
        "himself",
        "his",
        "hither",
        "hopefully",
        "how",
        "howbeit",
        "however",
        "i",
        "i'd",
        "i'll",
        "i'm",
        "i've",
        "ie",
        "if",
        "ignored",
        "immediate",
        "in",
        "inasmuch",
        "inc",
        "indeed",
        "indicate",
        "indicated",
        "indicates",
        "inner",
        "insofar",
        "instead",
        "into",
        "inward",
        "is",
        "isn't",
        "it",
        "it'd",
        "it'll",
        "it's",
        "its",
        "itself",
        "j",
        "just",
        "k",
        "keep",
        "keeps",
        "kept",
        "know",
        "known",
        "knows",
        "l",
        "last",
        "lately",
        "later",
        "latter",
        "latterly",
        "least",
        "less",
        "lest",
        "let",
        "let's",
        "like",
        "liked",
        "likely",
        "little",
        "look",
        "looking",
        "looks",
        "ltd",
        "m",
        "mainly",
        "many",
        "may",
        "maybe",
        "me",
        "mean",
        "meanwhile",
        "merely",
        "might",
        "more",
        "moreover",
        "most",
        "mostly",
        "much",
        "must",
        "my",
        "myself",
        "n",
        "name",
        "namely",
        "nd",
        "near",
        "nearly",
        "necessary",
        "need",
        "needs",
        "neither",
        "never",
        "nevertheless",
        "new",
        "next",
        "nine",
        "no",
        "nobody",
        "non",
        "none",
        "nor",
        "normally",
        "not",
        "nothing",
        "novel",
        "now",
        "nowhere",
        "o",
        "obviously",
        "of",
        "off",
        "often",
        "oh",
        "ok",
        "okay",
        "old",
        "on",
        "once",
        "one",
        "ones",
        "only",
        "onto",
        "or",
        "other",
        "others",
        "otherwise",
        "ought",
        "our",
        "ours",
        "ourselves",
        "out",
        "outside",
        "over",
        "overall",
        "own",
        "p",
        "particular",
        "particularly",
        "per",
        "perhaps",
        "placed",
        "please",
        "plus",
        "possible",
        "presumably",
        "probably",
        "provides",
        "q",
        "que",
        "quite",
        "qv",
        "r",
        "rather",
        "rd",
        "re",
        "really",
        "reasonably",
        "regarding",
        "regardless",
        "regards",
        "relatively",
        "respectively",
        "right",
        "s",
        "said",
        "same",
        "saw",
        "say",
        "saying",
        "says",
        "second",
        "secondly",
        "see",
        "seeing",
        "seem",
        "seemed",
        "seeming",
        "seems",
        "seen",
        "self",
        "selves",
        "sensible",
        "sent",
        "serious",
        "seriously",
        "seven",
        "several",
        "shall",
        "she",
        "should",
        "shouldn't",
        "since",
        "six",
        "so",
        "some",
        "somebody",
        "somehow",
        "someone",
        "something",
        "sometime",
        "sometimes",
        "somewhat",
        "somewhere",
        "soon",
        "sorry",
        "specified",
        "specify",
        "specifying",
        "still",
        "sub",
        "such",
        "sup",
        "sure",
        "t",
        "t's",
        "take",
        "taken",
        "tell",
        "tends",
        "th",
        "than",
        "thank",
        "thanks",
        "that",
        "that's",
        "the",
        "their",
        "theirs",
        "them",
        "themselves",
        "then",
        "thence",
        "there",
        "there's",
        "thereafter",
        "thereby",
        "therefore",
        "therein",
        "thereupon",
        "these",
        "they",
        "they'd",
        "they'll",
        "they're",
        "they've",
        "think",
        "third",
        "this",
        "thorough",
        "thoroughly",
        "those",
        "though",
        "three",
        "through",
        "throughout",
        "thus",
        "to",
        "together",
        "too",
        "took",
        "toward",
        "towards",
        "tried",
        "tries",
        "truly",
        "try",
        "trying",
        "twice",
        "two",
        "u",
        "un",
        "under",
        "unfortunately",
        "unless",
        "unlikely",
        "until",
        "unto",
        "up",
        "upon",
        "us",
        "use",
        "used",
        "useful",
        "uses",
        "using",
        "usually",
        "v",
        "value",
        "various",
        "very",
        "via",
        "viz",
        "vs",
        "w",
        "want",
        "wants",
        "was",
        "wasn't",
        "way",
        "we",
        "we'd",
        "we'll",
        "we're",
        "we've",
        "welcome",
        "well",
        "went",
        "were",
        "weren't",
        "what",
        "what's",
        "whatever",
        "when",
        "whence",
        "whenever",
        "where",
        "where's",
        "whereas",
        "whereby",
        "wherein",
        "whereupon",
        "wherever",
        "whether",
        "which",
        "while",
        "whither",
        "who",
        "who's",
        "whoever",
        "whole",
        "whom",
        "whose",
        "why",
        "will",
        "willing",
        "wish",
        "with",
        "within",
        "without",
        "won't",
        "wonder",
        "would",
        "wouldn't",
        "x",
        "y",
        "yes",
        "yet",
        "you",
        "you'd",
        "you'll",
        "you're",
        "you've",
        "your",
        "yours",
        "yourself",
        "yourselves",
        "z",
        "zero"
    ];

    /**
     * Generate n-grams
     * Credits to https://github.com/yooper/php-text-analysis/blob/master/src/NGrams/NGramFactory.php
     *
     * @param array  $tokens
     * @param        $ngramSize
     * @param string $separator
     *
     * @return array
     */
    private function generateNgrams(array $tokens, $ngramSize, $separator = ' '): array
    {
        $separatorLength = strlen($separator);
        $length = count($tokens) - $ngramSize + 1;

        if ($length < 1) {
            return [];
        }

        $ngrams = [];
        for ($i = 0; $i < $length; $i++) {
            // reset token and sub indexes
            $token = '';
            $subIndexes = [];
            for ($j = 0; $j < $ngramSize; $j++) {
                $subIndex = $i + $j;
                $subIndexes[] = $subIndex;
                $token .= $tokens[$subIndex];

                if ($j < $ngramSize - $separatorLength) {
                    $token .= $separator;
                }
            }

            $ngrams[] = ['token' => $token, 'indexes' => $subIndexes];
        }

        return $ngrams;
    }

    private function removePunctuation($word): string
    {
        $searchFor = [
            '!','#','$','%','&','(',')','*','+',"'",',',
            '\\','-','.','/',':',';','<','=','>','?','@',
            '^','_','`','{','|','}','~','[',']'
        ];

        return trim($word, " \t\n\r\0\x0B" . implode('', $searchFor));
    }

    private function isStopWord($word): bool
    {
        return in_array($word, self::STOP_WORDS);
    }
    
    private function isWhitelisted($word): bool
    {
        return in_array($word, $this->getWhitelist());
    }

    private function isBlackListed($word): bool
    {
        return in_array($word, $this->getBlacklist());
    }

    private function filterWordsByIndexes($words, $indexes)
    {
        foreach ($indexes as $index) {
            unset($words[$index]);
        }

        return $words;
    }

    public function run($text)
    {
        $text = mb_strtolower($text, 'utf-8');

        $words = (new WhitespaceTokenizer())->tokenize($text);

        foreach ($words as $key => $word) {
            $words[$key] = $this->removePunctuation($word);
        }

        $keywords = [];
        foreach (self::NGRAM_SIZES as $ngramSize) {
            $triNgrams = $this->generateNgrams($words, $ngramSize);

            foreach ($triNgrams as $wordAndIndexes) {
                $word = $wordAndIndexes['token'];

                if ($this->isWhitelisted($word) === true) {
                    // can be added
                    $keywords[] = $word;
                    $words = $this->filterWordsByIndexes($words, $wordAndIndexes['indexes']);
                } elseif ($this->isBlackListed($word) === true) {
                    // should be removed from the original
                    $words = $this->filterWordsByIndexes($words, $wordAndIndexes['indexes']);
                }
            }
        }

        return $this->extractKeywordsFromWords($words, $keywords);
    }

    private function extractKeywordsFromWords($words, $existingKeywords = [])
    {
        $stemmer = new PorterStemmer();
        foreach ($words as $word) {
            if ($this->isWhitelisted($word) === true) {
                $existingKeywords[] = $word;
                continue;
            }

            // remove stop words AND blocked ones
            if ($this->isStopWord($word) === true || $this->isBlackListed($word) === true) {
                continue;
            }

            $existingKeywords[] = $stemmer->stem($word);
        }

        return $existingKeywords;
    }

    /**
     * @return array
     */
    public function getBlacklist(): array
    {
        if (!isset($this->blacklist)) {
            return [];
        }

        return $this->blacklist;
    }

    /**
     * @param array $blacklist
     */
    public function setBlacklist(array $blacklist): void
    {
        $this->blacklist = $blacklist;
    }

    /**
     * @return array
     */
    public function getWhitelist(): array
    {
        if (!isset($this->whitelist)) {
            return [];
        }

        return $this->whitelist;
    }

    /**
     * @param array $whitelist
     */
    public function setWhitelist(array $whitelist): void
    {
        $this->whitelist = $whitelist;
    }
}
