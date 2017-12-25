<?php
/**
 * Created by PhpStorm.
 * User: sh
 * Date: 20.12.17
 * Time: 23:45
 */

namespace Tests\Shdev\FlashText\KeywordProcessor;

use Shdev\FlashText\KeywordProcessor;

class ExtractSpanTest extends \PHPUnit_Framework_TestCase
{
    /** @var array */
    private $testData;

    public function testExtractKeywords()
    {
        foreach ($this->testData as $testId => $testCase) {
            $keywordProcessor = new KeywordProcessor();

            foreach ($testCase['keyword_dict']  as $keywords) {
                $keywordProcessor->addKeywordsFromArray($keywords);
            }
            $keywordsExtracted = $keywordProcessor->extractKeywords($testCase['sentence'], true);

            foreach ($keywordsExtracted as $kwd) {
                $this->assertEquals(mb_strtolower(substr($testCase['sentence'], $kwd[1], $kwd[2] - $kwd[1])), mb_strtolower($kwd[0]),
                    sprintf('keywords span don\'t match the expected results for test case: %s', $testId)
                    );
            }
        }
    }

    public function testExtractKeywordsCaseSensitive()
    {
        foreach ($this->testData as $testId => $testCase) {
            $keywordProcessor = new KeywordProcessor(true);

            foreach ($testCase['keyword_dict'] as $keywords) {
                $keywordProcessor->addKeywordsFromArray($keywords);
            }
            $keywordsExtracted = $keywordProcessor->extractKeywords($testCase['sentence'], true);

            foreach ($keywordsExtracted as $kwd) {
                $this->assertEquals(mb_strtolower(substr($testCase['sentence'], $kwd[1], $kwd[2] - $kwd[1])), mb_strtolower($kwd[0]),
                    sprintf('keywords span don\'t match the expected results for test case: %s', $testId)
                );
            }
        }
    }

    protected function setUp()
    {
        $testData = file_get_contents(__DIR__ . '/keyword_extractor_test_cases.json');
        $this->testData = json_decode($testData, true);
    }


}
