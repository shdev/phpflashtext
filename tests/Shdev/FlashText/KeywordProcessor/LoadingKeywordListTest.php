<?php
/**
 * Created by PhpStorm.
 * User: sh
 * Date: 20.12.17
 * Time: 23:45
 */

namespace Tests\Shdev\FlashText\KeywordProcessor;

use Shdev\FlashText\KeywordProcessor;

class LoadingKeywordListTest extends \PHPUnit_Framework_TestCase
{

    public function testListLoading()
    {
        $keywordProcessor= new KeywordProcessor();
        $keywordList= ['java', 'product management'];
        $keywordProcessor->addKeywordsFromArray(    $keywordList);
        $sentence = 'I know java and product management';
        $keywordsExtracted = $keywordProcessor->extractKeywords($sentence);

        $this->assertEquals(['java', 'product management'], $keywordsExtracted);
        $sentenceNew = $keywordProcessor->replaceKeywords($sentence);
        $this->assertEquals('I know java and product management', $sentenceNew);
    }
}
