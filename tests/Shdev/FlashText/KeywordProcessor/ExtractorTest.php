<?php
/**
 * Created by PhpStorm.
 * User: sh
 * Date: 20.12.17
 * Time: 23:45
 */

namespace Tests\Shdev\FlashText\KeywordProcessor;

use Shdev\FlashText\KeywordProcessor;

class ExtractorTest extends \PHPUnit_Framework_TestCase
{
    /** @var array */
    private $testData;

    protected function setUp()
    {
        $testData = file_get_contents(__DIR__ . '/keyword_extractor_test_cases.json');
        $this->testData = json_decode($testData, true);
    }

    public function testExtractKeywords()
    {
        foreach ($this->testData as $testId => $testCase) {
            $keywordProcessor = new KeywordProcessor();
            $keywordProcessor->addKeywordsFromAssocArray($testCase['keyword_dict']);
            $keywordsExtracted = $keywordProcessor->extractKeywords($testCase['sentence']);
            $this->assertEquals($testCase['keywords'], $keywordsExtracted, sprintf('keywords_extracted don\'t match the expected results for test case: %s', $testId));
        }
    }

    public function testExtractKeywordsCaseSensitive()
    {
        foreach ($this->testData as $testId => $testCase) {
            $keywordProcessor= new KeywordProcessor(true);
            $keywordProcessor->addKeywordsFromAssocArray($testCase['keyword_dict']);
            $keywordsExtracted = $keywordProcessor->extractKeywords($testCase['sentence']);
            $this->assertEquals($testCase['keywords_case_sensitive'], $keywordsExtracted,
            sprintf('keywords_extracted don\'t match the expected results for test case: %s', $testId));
        }
    }

}
