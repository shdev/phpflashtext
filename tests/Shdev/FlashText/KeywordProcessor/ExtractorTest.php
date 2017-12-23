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
            dump(['keyword_dict' => $testCase['keyword_dict'], 'sentence' => $testCase['sentence'], 'keywords' => $testCase['keywords']]);
            $keywordProcessor = new KeywordProcessor();
            $keywordProcessor->addKeywordsFromAssocArray($testCase['keyword_dict']);
            $keywordsExtracted = $keywordProcessor->extractKeywords($testCase['sentence']);
            $this->assertEquals($testCase['keywords'], $keywordsExtracted, sprintf('keywords_extracted don\'t match the expected results for test case: %s', $testId));
        }
    }

    public function testExtractKeywordsCaseSensitive()
    {
        foreach ($this->testData as $testId => $testCase) {
            dump(['keyword_dict' => $testCase['keyword_dict'], 'sentence' => $testCase['sentence'], 'keywords' => $testCase['keywords_case_sensitive']]);
            $keywordProcessor= new KeywordProcessor(true);
            $keywordProcessor->addKeywordsFromAssocArray($testCase['keyword_dict']);
            $keywordsExtracted = $keywordProcessor->extractKeywords($testCase['sentence']);
            $this->assertEquals($testCase['keywords_case_sensitive'], $keywordsExtracted,
            sprintf('keywords_extracted don\'t match the expected results for test case: %s', $testId));
        }
    }

//def test_extract_keywords_case_sensitive(self):
//"""For each of the test case initialize a new KeywordProcessor.
//        Add the keywords the test case to KeywordProcessor.
//        Extract keywords and check if they match the expected result for the test case.
//
//        """
//for test_id, test_case in enumerate(self.test_cases):
//keyword_processor = KeywordProcessor(case_sensitive=True)
//keyword_processor.add_keywords_from_dict(test_case['keyword_dict'])
//keywords_extracted = keyword_processor.extract_keywords(test_case['sentence'])
//self.assertEqual(keywords_extracted, test_case['keywords_case_sensitive'],
//"keywords_extracted don't match the expected results for test case: {}".format(test_id))

}
