<?php

namespace KeywordExtractor;

use PHPUnit\Framework\TestCase;

class KeywordExtractorTest extends TestCase
{
    public function testGenerateNgram()
    {
        $greeting = new KeywordExtractor();
        $input = ['this', 'is', 'an', 'example'];

        $ngrams = $greeting->generateNgram($input, 1);
        $this->assertEquals($input, $ngrams);

        $expected = ['this is', 'is an', 'an example'];

        $ngrams = $greeting->generateNgram($input, 2);
        $this->assertEquals($expected, $ngrams);

        $expected = ['this is an', 'is an example'];

        $ngrams = $greeting->generateNgram($input, 3);
        $this->assertEquals($expected, $ngrams);

        $expected = ['this is an example'];

        $ngrams = $greeting->generateNgram($input, 4);
        $this->assertEquals($expected, $ngrams);

        $input = ['this', 'is'];

        $ngrams = $greeting->generateNgram($input, 4);
        $this->assertEquals([], $ngrams);

        $bigText = "A more principled way to estimate sentence importance is using random walks 
and eigenvector centrality. LexRank[5] is an algorithm essentially identical 
to TextRank, and both use this approach for document summarization. The two 
methods were developed by different groups at the same time, and LexRank 
simply focused on summarization, but could just as easily be used for
keyphrase extraction or any other NLP ranking task. A more principled way to 
estimate sentence importance is using random walks 
and eigenvector centrality. LexRank[5] is an algorithm essentially identical 
to TextRank, and both use this approach for document summarization. The two 
methods were developed by different groups at the same time, and LexRank 
simply focused on summarization, but could just as easily be used for
keyphrase extraction or any other NLP ranking task. A more principled way to 
estimate sentence importance is using random walks 
and eigenvector centrality. LexRank[5] is an algorithm essentially identical 
to TextRank, and both use this approach for document summarization. The two 
methods were developed by different groups at the same time, and LexRank 
simply focused on summarization, but could just as easily be used for
keyphrase extraction or any other NLP ranking task. A more principled way to 
estimate sentence importance is using random walks 
and eigenvector centrality. LexRank[5] is an algorithm essentially identical 
to TextRank, and both use this approach for document summarization. The two 
methods were developed by different groups at the same time, and LexRank 
simply focused on summarization, but could just as easily be used for
keyphrase extraction or any other NLP ranking task. A more principled way to 
estimate sentence importance is using random walks 
and eigenvector centrality. LexRank[5] is an algorithm essentially identical 
to TextRank, and both use this approach for document summarization. The two 
methods were developed by different groups at the same time, and LexRank 
simply focused on summarization, but could just as easily be used for
keyphrase extraction or any other NLP ranking task. A more principled way to 
estimate sentence importance is using random walks 
and eigenvector centrality. LexRank[5] is an algorithm essentially identical 
to TextRank, and both use this approach for document summarization. The two 
methods were developed by different groups at the same time, and LexRank 
simply focused on summarization, but could just as easily be used for
keyphrase extraction or any other NLP ranking task.simply focused on 
summarization, but could just as easily be used for
keyphrase extraction or any other NLP ranking task. A more principled way to 
estimate sentence importance is using random walks 
and eigenvector centrality. LexRank[5] is an algorithm essentially identical 
to TextRank, and both use this approach for document summarization. The two 
methods were developed by different groups at the same time, and LexRank 
simply focused on summarization, but could just as easily be used for
keyphrase extraction or any other NLP ranking task. A more principled way to 
estimate sentence importance is using random walks 
and eigenvector centrality. LexRank[5] is an algorithm essentially identical 
to TextRank, and both use this approach for document summarization. The two 
methods were developed by different groups at the same time, and LexRank 
simply focused on summarization, but could just as easily be used for
keyphrase extraction or any other NLP ranking task.";

        $bigTextArray = explode(' ', $bigText);

        //for ($i = 0; $i < 1500; $i++) {
            $ngrams = $greeting->generateNgram($bigTextArray, 1);
        //}

        $this->assertEquals(530, count($ngrams));
    }
}
